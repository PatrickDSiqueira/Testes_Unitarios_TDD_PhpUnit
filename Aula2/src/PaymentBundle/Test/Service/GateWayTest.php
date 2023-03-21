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
        // FASE DE PREPARAÇÃO
        $httpClient = $this->createMock(HttpClientInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $user = 'test';
        $password = 'invalid-password';
        $gateway = new Gateway($httpClient, $logger, $user, $password);

        //FASE DE EXECUÇÃO
        $map = [
            [
                'POST',
                Gateway::BASE_URL . '/authenticate',
                [
                    'user' => $user,
                    'password' => $password
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


        $paid = $gateway->pay('Patrick Siqueira', 1111222233334444, new \DateTime('now'), 100);

        // FASE DE ASSERÇÃO
        $this->assertEquals($paid, false);
    }

    /**
     * @test
     */
    public function shouldNotPayWhenFailOnGateway()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $user = 'test';
        $password = 'valid-password';
        $gateway = new Gateway($httpClient, $logger, $user, $password);
        $validity = new \DateTime('now');

        $name = 'Patrick Siqueira';
        $credit_card_number = '1111222233334444';
        $value = 100;
        $token = 'meu-token';

        $httpClient
            ->expects($this->at(0))
            ->method('send')
            ->willReturn($token);

        $httpClient
            ->expects($this->at(1))
            ->method('send')
            ->willReturn(['paid' => false]);


        $paid = $gateway->pay($name, $credit_card_number, $validity, $value);
        $this->assertEquals($paid, false);
    }

    /**
     * @test
     */
    public function shouldSuccessfullyPayWhenGatewayReturnOk()
    {

        $httpClient = $this->createMock(HttpClientInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $user = 'test';
        $password = 'valid-password';
        $gateway = new Gateway($httpClient, $logger, $user, $password);

        $name = 'Patrick Siqueira';
        $credit_card_number = '1111222233334444';
        $value = 100;
        $token = 'meu-token';

        $validity = new \DateTime('now');
        $map = [
            [
                'POST',
                Gateway::BASE_URL . '/authenticate',
                [
                    'user' => $user,
                    'password' => $password
                ],
                $token
            ],
            [
                'POST',
                Gateway::BASE_URL . '/pay',
                [
                    'name' => $name,
                    'credit_card_number' => $credit_card_number,
                    'validity' => $validity,
                    'value' => $value,
                    'token' => $token
                ],
                ['paid' => true]
            ]
        ];

        $httpClient
            ->expects($this->atLeast(2))
            ->method('send')
            ->will($this->returnValueMap($map));

        $paid = $gateway->pay($name, $credit_card_number, $validity, $value);
        $this->assertEquals(true, $paid);
    }
}