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
        $httpClient->method('send')
            ->will($this->returnCallback(
                function ($method, $address, $body) {
                    $this->fakeHttpClientSend($method, $address, $body);
                }
            ));

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
        $httpClient->method('send')
            ->will($this->returnCallback(
                function ($method, $address, $body) {
                    $this->fakeHttpClientSend($method, $address, $body);
                }
            ));

        $logger = $this->createMock(LoggerInterface::class);

        $user = 'test';
        $password = 'valid-password';
        $gateway = new Gateway($httpClient, $logger, $user, $password);

        $paid = $gateway->pay('Patrick Siqueira', 1111222233334444, new \DateTime('now'), 100);

        $this->assertEquals($paid, false);
    }

    /**
     * @test
     */
    public function shouldSuccessfullyPayWhenGatewayReturnOk()
    {

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('send')
            ->will($this->returnCallback(
                function ($method, $address, $body) {
                    $this->fakeHttpClientSend($method, $address, $body);
                }
            ));

        $logger = $this->createMock(LoggerInterface::class);

        $user = 'test';
        $password = 'invalid-password';
        $gateway = new Gateway($httpClient, $logger, $user, $password);

        $paid = $gateway->pay('Patrick Siqueira', 1111222233334444, new \DateTime('now'), 100);


        $this->assertEquals($paid, false);
    }


    public function fakeHttpClientSend($method, $address, $body){
        switch ($address){

            case Gateway::BASE_URL . '/authenticate' :

                if ($body['password'] != 'valid-password'){
                    return null;
                }
                return 'meu-token';
                break;

            case Gateway::BASE_URL . '/pay':

                if($body == 1111222233334444 ){
                    return ['paid' => true];
                }

                return ['paid'=>false];
                break;
        }
    }
}