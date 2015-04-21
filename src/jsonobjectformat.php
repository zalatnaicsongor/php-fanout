<?php

/*  jsonobjectformat.php
    ~~~~~~~~~
    This module implements the JsonObjectFormat class.
    :authors: Konstantin Bokarius.
    :copyright: (c) 2015 by Fanout, Inc.
    :license: MIT, see LICENSE for more details. */

namespace Fanout;

// The JSON object format used for publishing messages to Fanout.io.
class JsonObjectFormat extends \PubControl\Format
{
    private $value = null;

    // Initialize with a value representing the message to be sent.
    public function __construct($value)
    {
        $this->value = $value;
    }

    // The name of the format.
    public function name()
    {
        return 'json-object';
    }

    // The method used to export the format data.
    public function export()
    {
        return $this->value;
    }
}
?>
