<?php

include 'autoloader.php';

// Dessa forma o teste fica um pouco complicado, pois a cada teste que deve ser realizado o valor é incuído manualmente, e de forma individual.
// A ideia seria criar um isntância de todos os cenários possíveis, dessa forma há uma economia de tempo e código

//$discountCalculatorTest = new DiscountCalculatorTest();
//$discountCalculatorTest->ShouldApply_WhenValueIsAboveTheMinimumTest();

foreach (new DirectoryIterator( __DIR__) as $file){

    if (substr($file->getFilename(), -8) !== 'Test.php'){
        continue;
    }

    $className = substr($file->getFilename(), 0,-4);
    $testClass = new $className();

    $methods = get_class_methods($testClass);
    foreach ($methods as $method){
        $testClass->$method();
    }
};
// DirectoryInterator lista todos os arquivos do directory atual