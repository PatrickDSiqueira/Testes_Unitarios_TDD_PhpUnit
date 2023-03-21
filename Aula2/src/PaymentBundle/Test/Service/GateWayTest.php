<?php

namespace PaymentBundle\Test\Service;

use MyFramework\HttpClientInterface;
use MyFramework\LoggerInterface;
use PaymentBundle\Service\Gateway;
use PHPUnit\Framework\TestCase;

class GateWayTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotPayWhenAuthenticationFail()
    {

        $httpClient = $this->createMock(HttpClientInterface::class);
        $map = [
            [
                'POST',
                Gateway::BASE_URL . '/authenticate',
                [
                    'user' => 'teste',
                    'password' => 'invalid-password'
                ],
                null
            ],
        ];
        // PhpUnit entende a quantidade de parâmetros que precisa satisfazer e o que excede ele passa como inserção
        // Ele tá meio que fazendo um papel de stumb, pq está sendo passado somente o que ele precisa retornar

        $httpClient
            ->expects($this->once())
            // Aqui ele vira um MOCK pois aqui está sendo feita uma asserção no comportamento
            ->method('send')
            ->will($this->returnValueMap($map));

        $logger = $this->createMock(LoggerInterface::class);

        $user = 'test';
        $password = 'invalid-password';
        $gateway = new Gateway($httpClient, $logger, $user, $password);

        $paid = $gateway->pay('Patrick Siqueira', 1111222233334444, new \DateTime('now'), 100);


        $this->assertEquals($paid, false);
    }

    /**
     * @test
     */
    public function shouldNotPayWhenFailOnGateway()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);

        $validity = new \DateTime('now');
        $map = [
            [
                'POST',
                Gateway::BASE_URL . '/authenticate',
                [
                    'user' => 'test',
                    'password' => 'valid-password'
              ],
                'meu-token'
            ],
            [
                'POST',
                Gateway::BASE_URL . '/pay',
                [
                    'name' => 'Patrick Siqueira',
                    'credit_card_number' => 1111222233334444,
                    'validity' => $validity,
                    'value' => 100,
                    'token' => 'meu-token'
                ],
                ['paid' => false]
            ]
        ];

        $httpClient
            ->expects($this->atLeast(2))
            ->method('send')
            ->will($this->returnValueMap($map));

        $logger = $this->createMock(LoggerInterface::class);

        $user = 'test';
        $password = 'valid-password';
        $gateway = new Gateway($httpClient, $logger, $user, $password);

        $paid = $gateway->pay('Patrick Siqueira', 1111222233334444, $validity, 100);

        $this->assertEquals($paid, false);
    }

    /**
     * @test
     */
    public function shouldSuccessfullyPayWhenGatewayReturnOk()
    {

        $httpClient = $this->createMock(HttpClientInterface::class);

        $validity = new \DateTime('now');
        $map = [
            [
                'POST',
                Gateway::BASE_URL . '/authenticate',
                [
                    'user' => 'test',
                    'password' => 'valid-password'
                ],
                'meu-token'
            ],
            [
                'POST',
                Gateway::BASE_URL . '/pay',
                [
                    'name' => 'Patrick Siqueira',
                    'credit_card_number' => 1111222233334444,
                    'validity' => $validity,
                    'value' => 100,
                    'token' => 'meu-token'
                ],
                ['paid' => true]
            ]
        ];

        $httpClient
            ->expects($this->atLeast(2))
            ->method('send')
            ->will($this->returnValueMap($map));

        $logger = $this->createMock(LoggerInterface::class);

        $user = 'test';
        $password = 'valid-password';
        $gateway = new Gateway($httpClient, $logger, $user, $password);

        $paid = $gateway->pay('Patrick Siqueira', 1111222233334444, $validity, 100);


        $this->assertEquals(true, $paid);
    }
}