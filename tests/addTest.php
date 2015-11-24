<?php
/**
 * The class addTest is a Test Class for the add class
 * Author: Yanick Schraner
 */
class addTest extends PHPUnit_Framework_TestCase{
    /**
     * @test
     */
    public function testAdd(){
        $myAdd = new add(1,3);
		$this -> assertEquals(4, $myAdd -> add()); //Test succeeds
        $this -> assertLessThan(5, $myAdd -> add()); // Test succeeds
        $this -> assertEquals(3, $myAdd -> add()); //Test fails
	}
}