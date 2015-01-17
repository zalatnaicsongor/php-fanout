<?php

/*  fanout.php
    ~~~~~~~~~
    This module implements the Fanout class.
    :authors: Konstantin Bokarius.
    :copyright: (c) 2015 by Fanout, Inc.
    :license: MIT, see LICENSE for more details. */

class Fanout
{
	public $realm = null;
	public $key = null;
	public $ssl = null;
	public $pub = null;	

    public function __construct($realm, $key, $ssl=true)
	{
		$this->realm = $realm;
		$this->key = $key;
		$this->ssl = $ssl;
		register_shutdown_function(array($this, 'finish'));
	}

	public function publish($channel, $data, $id=null, $prev_id=null)
	{
		$pub = $this->get_pubcontrol();
		$pub->publish($channel, new Item(new JsonObjectFormat($data),
				$id, $prev_id));
	}

	public function publish_async($channel, $data, $id=null, $prev_id=null,
			$callback=null)
	{
		$pub = $this->get_pubcontrol();
		$pub->publish_async($channel, new Item(new JsonObjectFormat($data),
				$id, $prev_id), $callback);
	}

	public function finish()
	{
		$pub = $this->get_pubcontrol();
		$pub->finish();		
	}

	// TODO: Use thread variable.
	private function get_pubcontrol()
	{
		if (is_null($this->pub))
		{
			$scheme = null;
			if ($this->ssl)
				$scheme = 'https';
			else
				$scheme = 'http';
			$this->pub = new PubControlClient(
					"{$scheme}://api.fanout.io/realm/" . "{$this->realm}");
			$this->pub->set_auth_jwt(array('iss' => $this->realm),
					base64_decode($this->key));
		}
		return $this->pub;
	}
}
?>
