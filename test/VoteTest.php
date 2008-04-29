<?php

require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');

class VoteTest extends UnitTestCase {

    function setUp()
    {
        require_once '../lib/vote.php';

		$this->db    =& new PDO("sqlite:heer-test.db");
		Vote::set_db($this->db);
		$this->vote = new Vote("vote:2008", "test");
    }

    function tearDown()
    {
		$tableName = Vote::get_table_name();
		$id = $this->vote->id;
		$this->db->exec("delete from $tableName where id='$id'");
    }

	function test_save()
	{
		$this->assertTrue($this->vote->save());
		$this->assertFalse($this->vote->save());
	}

}
// Running the test.
$test =& new VoteTest;
$test->run(new HtmlReporter());
?>

