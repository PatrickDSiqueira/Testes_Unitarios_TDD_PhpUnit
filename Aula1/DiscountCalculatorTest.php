<?php

Class DiscountCalculatorTest {

    public function ShouldApply_WhenValueIsAboveTheMinimumTest(){

        // Três fases do teste
            // * Preparação
            // * Execução
            // * Asserção


        // 1 - Preparação
        $discountCalculator = new DiscountCalculator();

        // 2 - Execução
        $totalValue = 130;
        $totalWithDiscount = $discountCalculator->apply($totalValue);

        // 3 - Asserção
        $expectedValue = 110;
        $this->assertEquals($expectedValue, $totalWithDiscount);
    }

    public function ShouldNotApply_WhenValueIsBellowTheMinimumTest(){

        $discountCalculator = new DiscountCalculator();

        $totalValue = 90;
        $totalWithDiscount = $discountCalculator->apply($totalValue);

        $expectedValue = 90;
        $this->assertEquals($expectedValue, $totalWithDiscount);
    }

    public function assertEquals($expectedValue, $actualValue){

        if ($expectedValue !== $actualValue){
            $message = 'Expected:' . $expectedValue . 'but got: ' . $actualValue;
            throw new \Exception($message);
        }

        echo "Test passed! \n";
    }
}