<?php

/*  jsonobjectformat.php
    ~~~~~~~~~
    This module implements the JsonObjectFormat class.
    :authors: Konstantin Bokarius.
    :copyright: (c) 2015 by Fanout, Inc.
    :license: MIT, see LICENSE for more details. */

class JsonObjectFormat extends Format
{
    private $value = null;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function name()
    {
        return 'json-object';
    }

    public function export()
    {
        return $this->value;
    }
}
?>
