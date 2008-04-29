<?php
	class Vote
	{
		static $db;
		public $id, $uri, $user;

		function __construct($uri, $user)
		{
			$this->id   = md5($uri . $user);
			$this->uri  = $uri;
			$this->user = $user;
		}

		public static function get_table_name()
		{
			return "vote";
		}

		static function set_db($db, $user = NULL, $password = NULL)
		{
			if ($db instanceof PDO) {
				self::$db =& $db;
			} else {
				if (empty($user)) {
					self::$db =& new PDO($db);
				} else {
					self::$db =& new PDO($db, $user, $password);
				}
			}
			self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			return self::$db;
		}

		public function toSQLinsert()
		{
			$table = self::get_table_name();
			$id    = $this->id;
			$uri   = $this->uri;
			$user  = $this->user;
			$date  = date('c');
			$query = "insert into $table (id, uri, user, date) values ('$id', '$uri', '$user', '$date')";
			return $query;
		}

		function save($db = NULL)
		{
			if (!$db) $db =& self::$db;
			$query = $this->toSQLinsert();
			$id = $this->id;
			$table = self::get_table_name();
			try {
				$db->exec($query);
			} catch (PDOException $e) {
				if ($db->errorCode() == "23000") { // l'id existe dans la table
					return false;
				} else {
					die($e->getMessage() .' [<b>'.  $query .'</b>]');
				}
			}
			return $this;
		}
	}
