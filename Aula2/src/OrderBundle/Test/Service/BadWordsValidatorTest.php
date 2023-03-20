<?php

namespace OrderBundle\Test\Service;

use OrderBundle\Repository\BadWordsRepository;
use OrderBundle\Service\BadWordsValidator;
use PHPUnit\Framework\TestCase;

class BadWordsValidatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider badWordsDataProvider
     */

    public function hasBadWords( $badWordslist, $text, $foundBadWords)
    {

        $badWordsReposiroty = $this->createMock(BadWordsRepository::class);
        // Ele cria um stub, um objeto falso somente para a gente usar nos nossos testes

        $badWordsReposiroty->method('findAllAsArray')
            ->willReturn($badWordslist);

        $badWordsValidator = new BadWordsValidator($badWordsReposiroty);

        $hasBadWorld = $badWordsValidator->hasBadWords($text);

        $this->assertEquals($foundBadWords, $hasBadWorld);

    }

    public function badWordsDataProvider()
    {

        return [
            'shouldFindWhenHasBadWords' => [
                'badWordsList' => ['bobo', 'chule', 'besta'],
                'text' => 'Seu restaurante e muito bobo',
                'foundBadWords' => true
            ],
            'shouldNotFindWhenHasNoBadWords' => [
                'badWordsList' => ['bobo', 'chule', 'besta'],
                'text' => 'Trocar batata',
                'foundBadWords' => false
            ],
            'shouldNotFindWhenTextIsEmpty' => [
                'badWordsList' => ['bobo', 'chule', 'besta'],
                'text' => '',
                'foundBadWords' => false
            ],
            'shouldFindWhenBadListIsEmpty' => [
                'badWordsList' => [],
                'text' => 'Seu restaurante e muito bobo',
                'foundBadWords' => false
            ],
        ];
    }


}