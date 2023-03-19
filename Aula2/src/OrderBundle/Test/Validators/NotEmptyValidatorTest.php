<?php
 namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\NotEmptyValidator;
use PHPUnit\Framework\TestCase;

class NotEmptyValidatorTest extends TestCase {
    /**
     * @dataProvider valueProvider
     */
    public function testIsValid($value, $expectedResult){

            $notEmptyValidator = new NotEmptyValidator($value);

            $isValid = $notEmptyValidator->isValid();

            $this->assertEquals($expectedResult, $isValid);
    }

    public function valueProvider()
    {

        return [
            'shouldBeValidWhenValueISNotEmpty' => ['value' => 'foo', 'expectedResult' => true],
            'shouldNotBeValidWhenValueISNotEmpty' => ['value' => '', 'expectedResult' => false]
        ];
    }
}