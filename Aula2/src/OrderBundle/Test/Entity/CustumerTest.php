<?php

namespace OrderBundle\Validators\Test\Entity;

use PHPUnit\Framework\TestCase;
use OrderBundle\Entity\Customer;

class CustomerTest extends testCase {

    /**
     * @test
     * @dataProvider customerAllowedDataProvider
     */

    public function isAllowedToOrder($isActive, $isBlocked, $expectedAllowed){

        $customer = new Customer($isActive, $isBlocked, "Patrick Siqueira", '31984305054' );

        $isAllowed = $customer->isAllowedToOrder();

        $this->assertEquals($expectedAllowed, $isAllowed);
    }

    public function customerAllowedDataProvider(){
        return [
            'shouldBeAllowedWhenIsActiveAndNotBlocked' => [
                'isActive' => true,
                'isBlocked' => false,
                'expectedAllowed' => true
            ],
            'shouldNotBeAllowedWhenIsActiveButIsBlocked' => [
                'isActive' => true,
                'isBlocked' => true,
                'expectedAllowed' => false
            ],
            'shouldNotBeAllowedWhenIsNotActive' => [
                'isActive' => false,
                'isBlocked' => false,
                'expectedAllowed' => false
            ],
            'shouldNotBeAllowedWhenIsNotActiveAndIsBlocked' => [
                'isActive' => false,
                'isBlocked' => true,
                'expectedAllowed' => false
            ],
        ];
    }
}