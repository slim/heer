<?php

require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');

class LinkTest extends UnitTestCase {

    function setUp()
    {
        require_once '../lib/link.php';

		$this->id    = "http://localhost/test";
		$this->Link  = new Link($this->id, "test");
		$this->db    =& new PDO("sqlite:heer-test.db");
		$this->testId    = "http://localhost/in-db";
		$this->testLink  = new Link($this->testId, "in the test db");
		$this->testLink->value = 55;
		$this->testLink->date = "2008-04-14 11:20";

		Link::set_db($this->db);
    }

    function tearDown()
    {
		$tableName = Link::get_table_name();
		$id = $this->Link->id;
		$this->db->exec("delete from $tableName where id='$id'");
    }

    function test_toSQLinsert()
    {
        $result   = $this->Link->toSQLinsert();
        $expected = "/insert/i";
        $this->assertWantedPattern($expected, $result);
        $expected = "/'". addcslashes($this->id, '/.') ."'/";
        $this->assertWantedPattern($expected, $result);
    }

    function test_sql_select()
    {
		$options = " where value > 1";
        $result   = Link::sql_select($options);
        $expected = "/$options/i";
        $this->assertWantedPattern($expected, $result);
    }

    function test_select()
    {
		$result = Link::select("where id='". $this->testId ."'");
		$expected = array($this->testLink);
        $this->assertEqual($expected, $result);
    }

    function test_save()
    {
		$this->Link->save();
		$id = $this->Link->id;
		$result = $this->db->query("select title, value from link where id='$id'");
		$rows = $result->fetchAll(PDO::FETCH_ASSOC);
		$count = count($rows);
		$expected = 1;
        $this->assertEqual($expected, $count);
		$expected = "test";
        $this->assertEqual($expected, $rows[0]['title']);
		$firstSaveValue = $rows[0]['value'];
		$this->Link->save(); // save a second time
		$result = $this->db->query("select title, value from link where id='$id'");
		$rows = $result->fetchAll(PDO::FETCH_ASSOC);
		$count = count($rows);
		$expected = 1;
        $this->assertEqual($expected, $count);
		$secondSaveValue = $rows[0]['value'];
        $this->assertEqual($firstSaveValue + 1, $secondSaveValue);
    }

}
// Running the test.
$test =& new LinkTest;
$test->run(new HtmlReporter());
?>

