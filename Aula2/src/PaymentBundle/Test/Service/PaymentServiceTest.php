<?php

namespace  PaymentBundle\Test\Services;

use OrderBundle\Entity\CreditCard;
use OrderBundle\Entity\Customer;
use OrderBundle\Entity\Item;
use PaymentBundle\Exception\PaymentErrorException;
use PaymentBundle\Repository\PaymentTransactionRepository;
use PaymentBundle\Service\Gateway;
use PaymentBundle\Service\PaymentService;
use PHPUnit\Framework\TestCase;

class PaymentServiceTest extends TestCase {

    /**
     * @test
     */
    public function shouldSaveWhenGatewayReturnOkWithRetries()
    {
        $gateway = $this->createMock(Gateway::class);
        $paymentTransactionRepository = $this->createMock(PaymentTransactionRepository::class);

        $paymentService = new PaymentService($gateway, $paymentTransactionRepository);

        $gateway
            ->expects($this->atLeast(3))
            ->method('pay')
            ->will($this->onConsecutiveCalls(
                false, false, true
            ));

        $paymentTransactionRepository
            ->expects($this->once())
            ->method('save');

        $customer = $this->createMock(Customer::class);
        $item = $this->createMock(Item::class);
        $creditCard = $this->createMock(CreditCard::class);

        $paymentService->pay($customer, $item, $creditCard);
    }
    /**
     * @expectedException PaymentBundle\Exception\PaymentErrorException
     * @test
     */

    public function shouldThrowExceptionWhenGatewayFails(){
        $gateway = $this->createMock(Gateway::class);
        $paymentTransactionRepository = $this->createMock(PaymentTransactionRepository::class);

        $paymentService = new PaymentService($gateway, $paymentTransactionRepository);

        $gateway
            ->expects($this->atLeast(3))
            ->method('pay')
            ->will($this->onConsecutiveCalls(
                false, false, false
            ));

        $paymentTransactionRepository
            ->expects($this->never())
            ->method('save');

        $this->expectException(PaymentErrorException::class);

        $customer = $this->createMock(Customer::class);
        $item = $this->createMock(Item::class);
        $creditCard = $this->createMock(CreditCard::class);

        $paymentService->pay($customer, $item, $creditCard);
    }

}