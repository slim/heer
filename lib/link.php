<?php
	
	require_once "persistance.php";

	class Link implements sql, persistance
	{
		static $db;
		public $id, $title, $value, $date;

		function __construct($url, $title = NULL)
		{
			$this->id    = $url;
			$this->title = stripslashes($title);
			$this->value = 1;
			$this->date  = date('c');
		}

		public static function get_table_name()
		{
			return "link";
		}
		
		public function toSQLinsert()
		{
			$table = self::get_table_name();
			$id    = $this->id;
			$title = str_replace("'", "''", $this->title);
			$value = $this->value;
			$date  = $this->date;
			$query = "insert into $table (id, title, value, date) values ('$id', '$title', '$value', '$date')";
			return $query;
		}
		
		public static function select($options = NULL, $db = NULL)
		{
			if (!$db) $db =& self::$db;
			$query  = self::sql_select($options);
			$result = $db->query($query);
			$items  = array();
			foreach ($result as $r) {
				$s        = new Link($r['id'], $r['title']);
				$s->value = $r['value'];
				$s->date  = $r['date'];
				$items []= $s;
			}
			return $items;
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

		function delete($db = NULL)
		{
			if (!$db) $db =& self::$db;
			$id = $this->id;
			$table = self::get_table_name();
			$db->exec("delete from $table where id='$id'");

			return $this;
		}

		function load($db = NULL)
		{
			if (!$db) $db =& self::$db;
			$id = $this->id;
			list($link) = Link::select("where id='$id'", $db);
			$this->value = $link->value;

			return $this;
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
					$this->load();
					$this->value++;
					$this->delete();
					$query = $this->toSQLinsert();
					$db->exec($query);
				} else {
					die($e->getMessage() .' [<b>'.  $query .'</b>]');
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

		public function toRSSitem()
		{
			$rss  = '';
			$rss .= '<item>';
			$rss .= '<title>'. $this->title .'</title>';
			$rss .= '<link>'. $this->id .'</link>';
			$rss .= '</item>';
			return $rss;
		}
	}
