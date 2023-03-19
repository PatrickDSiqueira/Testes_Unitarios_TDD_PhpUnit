<?php

namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\NotEmptyValidator;
use PHPUnit\Framework\TestCase;

class NotEmptyValidatorTest extends TestCase {
    public function testIsValid(){

        $dataProvider = [
            "" => false,
            "foo" => false
        ];

        foreach ($dataProvider as $value => $expectedResult) {

            $notEmptyValidator = new NotEmptyValidator($value);

            $isValid = $notEmptyValidator->isValid();

            $this->assertEquals($expectedResult, $isValid);
        }
    }
}