<?php
	class ID
	{
		public $value;
		public static $seed;

		function __construct($value = NULL)
		{
			$this->value = $value ? $value : ID::gen();
		}
		static function set_seed($seed)
		{
			ID::$seed = $seed;
		}

		static function gen()
		{
			$base = uniqid();
			return $base .':'. md5($base . ID::$seed);
		}

		function isAuthentic($seed = NULL)
		{
			$seed = $seed ? $seed : ID::$seed;
			list($id, $md5) = explode(':', $this->value);
			return md5($id . $seed) == $md5;
		}
		
		function __toString()
		{
			return $this->value;
		}
	}
