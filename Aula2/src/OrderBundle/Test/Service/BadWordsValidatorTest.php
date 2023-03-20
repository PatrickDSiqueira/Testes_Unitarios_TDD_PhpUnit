<?php

namespace OrderBundle\Test\Service;

use OrderBundle\Service\BadWordsValidator;
use PHPUnit\Framework\TestCase;

class BadWordsValidatorTest extends TestCase {
    /**
     * @test
     * @dataProvider
     */

    public function hasBadWords(){

        $badWordsReposiroty = new BadWordsRepositoryStub();
        $badWordsValidator = new BadWordsValidator($badWordsReposiroty);

        $hasBadWorld = $badWordsValidator->hasBadWords('Seu restaurante é muito bom');

        $this->assertEquals(false, $hasBadWorld);

    }
}