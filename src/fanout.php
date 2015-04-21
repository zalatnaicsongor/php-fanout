<?php

/*  fanout.php
    ~~~~~~~~~
    This module implements the Fanout class.
    :authors: Konstantin Bokarius.
    :copyright: (c) 2015 by Fanout, Inc.
    :license: MIT, see LICENSE for more details. */

namespace Fanout;

// The Fanout class is used for publishing messages to Fanout.io and is
// configured with a Fanout.io realm and associated key. SSL can either
// be enabled or disabled. Note that unlike the PubControl class
// there is no need to call the finish method manually, as it will
// automatically be called when the calling program exits.
class Fanout
{
    public static $pub = null;
    public $realm = null;
    public $key = null;
    public $ssl = null;

    // Initialize with a specified realm, key, and a boolean indicating wther
    // SSL should be enabled or disabled.
    public function __construct($realm, $key, $ssl=true)
    {
        $this->realm = $realm;
        $this->key = $key;
        $this->ssl = $ssl;
        register_shutdown_function(array($this, 'finish'));
    }

    // Synchronously publish the specified data to the specified channel for
    // the configured Fanout.io realm. Optionally provide an ID and previous
    // ID to send along with the message.
    public function publish($channel, $data, $id=null, $prev_id=null)
    {
        $pub = $this->get_pubcontrol();
        $pub->publish($channel, new \PubControl\Item(
                new JsonObjectFormat($data), $id, $prev_id));
    }

    // Asynchronously publish the specified data to the specified channel for
    // the configured Fanout.io realm. Optionally provide an ID and previous ID
    // to send along with the message, as well a callback method that will be
    // called after publishing is complete and passed the result and error message
    // if an error was encountered.
    public function publish_async($channel, $data, $id=null, $prev_id=null,
            $callback=null)
    {
        $pub = $this->get_pubcontrol();
        $pub->publish_async($channel, new \PubControl\Item(
                new JsonObjectFormat($data), $id, $prev_id), $callback);
    }

    // The finish method is a blocking method that ensures that all asynchronous
    // publishing is complete prior to returning and allowing the consumer to 
    // proceed. Note that the finish method is automatically called when the
    // application exits.
    public function finish()
    {
        $pub = $this->get_pubcontrol();
        $pub->finish();        
    }

    // An internal method used for retrieving the PubControl instance. The
    // PubControl instance is saved as a static variable and if an instance
    // is not available when this method is called then one will be created.
    // TODO: Use thread variable.
    private function get_pubcontrol()
    {
        if (is_null(self::$pub))
        {
            $scheme = null;
            if ($this->ssl)
                $scheme = 'https';
            else
                $scheme = 'http';
            self::$pub = new \PubControl\PubControlClient(
                    "{$scheme}://api.fanout.io/realm/" . "{$this->realm}");
            self::$pub->set_auth_jwt(array('iss' => $this->realm),
                    base64_decode($this->key));
        }
        return self::$pub;
    }
}
?>
