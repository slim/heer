<?php

require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');

class IDTest extends UnitTestCase {

    function setUp()
    {
        require_once '../lib/id.php';

		ID::set_seed('test');
		$this->id = new ID();
		$this->customId = new ID();
		$this->customId->value = 555 .':'. md5('555test');
    }

    function tearDown()
    {
    }

	function test_wellFormedness()
	{
		$this->assertWantedPattern('/:/', $this->id->value);
	}

    function test_isAuthentic()
    {
        $this->assertTrue($this->id->isAuthentic());
        $this->assertTrue($this->customId->isAuthentic());
    }

}
// Running the test.
$test =& new IDTest;
$test->run(new HtmlReporter());
?>

