<?php

/**
 * Created by IntelliJ IDEA.
 * User: Tom Ohme, Norina Steiner
 * Date: 23.11.2015
 * Time: 11:02
 * Description: Customer Test Class
 */
class CustomerTest extends PHPUnit_Framework_TestCase {
    /**
     * @test
     */
    public function testCustomer() {
        $customer = new BcCustomer();
        $customer->setId(1);
        $customer->setName("Ohme");
        $customer->setFirstname("Tom");
        $customer->setEmail("tom.ohme@students.fhnw.ch");
        $customer->setGroupId(1);
        $customer->setGroupName("Group1");
        $customer->setHasNewsletter(true);
        $customer->setHasOrder(true);
        $customer->setIsDeleted(false);
        $this->assertEquals($customer->getId(),1);
        $this->assertEquals($customer->getName(),"Ohme");
        $this->assertEquals($customer->getFirstname(),"Tom");
        $this->assertEquals($customer->getEmail(),"tom.ohme@students.fhnw.ch");
        $this->assertEquals($customer->getGroupId(),1);
        $this->assertEquals($customer->getGroupName(),"Group1");
        $this->assertTrue($customer->getHasNewsletter());
        $this->assertTrue($customer->getHasOrder());
        $this->assertFalse($customer->getIsDeleted());
    }

}