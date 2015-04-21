<?php

class PubControlClientTestClass
{
    public $was_finish_called = false;
    public $was_publish_called = false;
    public $publish_channel = false;
    public $publish_item = false;
    public $publish_callback = null;

    public function finish()
    {
        $this->was_finish_called = true;   
    }

    public function publish($channel, $item)
    {
        $this->was_publish_called = true;
        $this->publish_channel = $channel;
        $this->publish_item = $item;
    }

    public function publish_async($channel, $item, $callback)
    {
        $this->was_publish_called = true;
        $this->publish_channel = $channel;
        $this->publish_item = $item;
        $this->publish_callback = $callback;
    }
}

class FanoutTests extends PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        $fo = new Fanout\Fanout('realm', 'key');
        $this->assertEquals($fo->realm, 'realm');
        $this->assertEquals($fo->key, 'key');
        $this->assertEquals($fo->ssl, true);
        $fo = new Fanout\Fanout('realm2', 'key2', false);
        $this->assertEquals($fo->realm, 'realm2');
        $this->assertEquals($fo->key, 'key2');
        $this->assertEquals($fo->ssl, false);
    }

    public function testPublish()
    {
        $fo = new Fanout\Fanout('realm', 'key');
        Fanout\Fanout::$pub = new PubControlClientTestClass();
        $fo->publish('chan', 'data');
        $this->assertEquals(Fanout\Fanout::$pub->publish_channel, 'chan');
        $this->assertEquals(Fanout\Fanout::$pub->publish_item->export(),
                (new PubControl\Item(new Fanout\JsonObjectFormat('data')))->export());
        $fo->publish('chan', 'data', 'id', 'prev-id');
        $this->assertEquals(Fanout\Fanout::$pub->publish_channel, 'chan');
        $this->assertEquals(Fanout\Fanout::$pub->publish_item->export(),
                (new PubControl\Item(new Fanout\JsonObjectFormat('data'),
                'id', 'prev-id'))->export());
    }

    public function testPublishAsync()
    {
        $fo = new Fanout\Fanout('realm', 'key');
        Fanout\Fanout::$pub = new PubControlClientTestClass();
        $fo->publish_async('chan', 'data');
        $this->assertEquals(Fanout\Fanout::$pub->publish_channel, 'chan');
        $this->assertEquals(Fanout\Fanout::$pub->publish_item->export(),
                (new PubControl\Item(new Fanout\JsonObjectFormat('data')))->export());
        $this->assertEquals(Fanout\Fanout::$pub->publish_callback, null);
        $fo->publish_async('chan', 'data', 'id', 'prev-id', 'callback');
        $this->assertEquals(Fanout\Fanout::$pub->publish_channel, 'chan');
        $this->assertEquals(Fanout\Fanout::$pub->publish_item->export(),
                (new PubControl\Item(new Fanout\JsonObjectFormat('data'),
                'id', 'prev-id'))->export());
        $this->assertEquals(Fanout\Fanout::$pub->publish_callback, 'callback');
    }

    public function testFinish()
    {
        $fo = new Fanout\Fanout('realm', 'key');
        Fanout\Fanout::$pub = new PubControlClientTestClass();
        $fo->finish();
        $this->assertEquals(Fanout\Fanout::$pub->was_finish_called, true);
    }

    public function testGetPubControl()
    {
        Fanout\Fanout::$pub = null;
        $fo = new Fanout\Fanout('realm', 'key');
        try
        {
            $fo->publish('chan', 'data');
        }
        catch (Exception $e) { }
        $this->assertEquals(Fanout\Fanout::$pub->uri,
                'https://api.fanout.io/realm/realm');
        $this->assertEquals(Fanout\Fanout::$pub->auth_jwt_claim,
                array('iss' => 'realm'));
        $this->assertEquals(Fanout\Fanout::$pub->auth_jwt_key,
                base64_decode('key'));
        Fanout\Fanout::$pub = null;
        $fo = new Fanout\Fanout('realm', 'key', false);
        try
        {
            $fo->publish('chan', 'data');
        }
        catch (Exception $e) { }
        $this->assertEquals(Fanout\Fanout::$pub->uri,
                'http://api.fanout.io/realm/realm');
        $this->assertEquals(Fanout\Fanout::$pub->auth_jwt_claim,
                array('iss' => 'realm'));
        $this->assertEquals(Fanout\Fanout::$pub->auth_jwt_key,
                base64_decode('key'));
    }
}

?>
