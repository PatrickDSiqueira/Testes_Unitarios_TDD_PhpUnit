<?php
 namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\CreditCardNumberValidator;
use PHPUnit\Framework\TestCase;

class CreditCardNumberValidatorTest extends TestCase {
    /**
     * @dataProvider valueProvider
     */
    public function testIsValid($value, $expectedResult){

            $creditCardNumberValidator = new CreditCardNumberValidator($value);

            $isValid = $creditCardNumberValidator->isValid();

            $this->assertEquals($expectedResult, $isValid);
    }

    public function valueProvider()
    {

        return [
            'shouldBeValidWhenValueIsACreditCard' => ['value'=>9999999999999999, 'expectedResult' => true],
            'shouldBeValidWhenValueIsACreditCardAsString' => ['value'=>'9999999999999999', 'expectedResult' => true],
            'shouldNotBeValidWhenValueIsNotACreditCard' => ['value'=>999999999, 'expectedResult' => false],
            'shouldNotBeValidWhenValueIsNotACreditCardAsString' => ['value'=>'99999999', 'expectedResult' => false],
            'shouldNotBeValidWhenValueIsEmpty' => ['value'=>'', 'expectedResult' => false],
        ];
    }
}