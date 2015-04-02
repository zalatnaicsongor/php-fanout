<?php

class JsonObjectFormatTests extends PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        $fmt = new JsonObjectFormat('value');
    }

    public function testName()
    {
        $fmt = new JsonObjectFormat('value');
        $this->assertEquals($fmt->name(), 'json-object');
    }

    public function testExport()
    {
        $fmt = new JsonObjectFormat('value');
        $this->assertEquals($fmt->export(), 'value');
    }
}

?>
