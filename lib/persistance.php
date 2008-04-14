<?php

	interface persistance
	{
		public static function set_db($db);
		public static function select($wildcards = NULL, $db = NULL);
		public function save($db = NULL);
	}

	interface sql
	{
		public function toSQLinsert();
		public static function sql_select($wildcards = NULL);
		public static function get_table_name();
	}	

	abstract class Storable
	{
		abstract public static function get_table_name();
		abstract public function toSQLinsert();
		abstract public static function select($wildcards = NULL, $db = NULL);

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
					$db->exec("delete from $table where id='$id'");
					$db->exec($query);
				} else {
					die($e->getMessage());
				}
			}
			return $this;
		}

		public static function sql_select($options = NULL)
		{
			$table = self::get_table_name();
			$query = "select * from $table $options";
			return $query;
		}
	}
