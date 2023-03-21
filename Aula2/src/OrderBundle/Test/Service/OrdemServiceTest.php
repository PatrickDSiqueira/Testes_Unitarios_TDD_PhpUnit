<?php

namespace PaymentBundle\Test\Service;

use FidelityProgramBundle\Service\FidelityProgramService;
use OrderBundle\Entity\CreditCard;
use OrderBundle\Entity\Customer;
use OrderBundle\Entity\Item;
use OrderBundle\Exception\BadWordsFoundException;
use OrderBundle\Exception\CustomerNotAllowedException;
use OrderBundle\Exception\ItemNotAvailableException;
use OrderBundle\Repository\OrderRepository;
use OrderBundle\Service\BadWordsValidator;
use OrderBundle\Service\OrderService;
use PaymentBundle\Entity\PaymentTransaction;
use PaymentBundle\Service\PaymentService;
use PHPUnit\Framework\TestCase;

class OrdemServiceTest extends TestCase
{

    private $badWordsValidator;
    private $paymentService;
    private $ordemRepository;
    private $fidelityProgramService;
    private $customer;
    private $item;
    private $creditCard;

    public function setUp()
    {
        $this->paymentService = $this->createMock(PaymentService::class);
        $this->fidelityProgramService = $this->createMock(FidelityProgramService::class);
        $this->badWordsValidator = $this->createMock(BadWordsValidator::class);
        $this->ordemRepository = $this->createMock(OrderRepository::class);

        $this->customer = $this->createMock(Customer::class);
        $this->item = $this->createMock(Item::class);
        $this->creditCard = $this->createMock(CreditCard::class);
    }

    /**
     * @test
     */
    public function shouldNotProcessWhenCustomerIsNotAllowed()
    {
        $ordemService = new OrderService(
            $this->badWordsValidator,
            $this->paymentService,
            $this->ordemRepository,
            $this->fidelityProgramService
        );

        $this->customer
            ->method('isAllowedToOrder')
            ->willReturn(false);

        $this->expectException(CustomerNotAllowedException::class);

        $ordemService->process(
            $this->customer,
            $this->item,
            "",
            $this->creditCard
        );
    }

    /**
     * @test
     */
    public function shouldNotProcessWhenItemIsNotAvailable()
    {
        $ordemService = new OrderService(
            $this->badWordsValidator,
            $this->paymentService,
            $this->ordemRepository,
            $this->fidelityProgramService
        );

        $this->customer
            ->method('isAllowedToOrder')
            ->willReturn(true);

        $this->item
            ->method('isAvailable')
            ->willReturn(false);

        $this->expectException(ItemNotAvailableException::class);

        $ordemService->process(
            $this->customer,
            $this->item,
            "",
            $this->creditCard
        );
    }

    /**
     * @test
     */
    public function shouldNotProcessWhenBadWordsIsFound()
    {
        $ordemService = new OrderService(
            $this->badWordsValidator,
            $this->paymentService,
            $this->ordemRepository,
            $this->fidelityProgramService
        );

        $this->customer
            ->method('isAllowedToOrder')
            ->willReturn(true);

        $this->item
            ->method('isAvailable')
            ->willReturn(true);

        $this->badWordsValidator
            ->method('hasBadWords')
            ->willReturn(true);

        $this->expectException(BadWordsFoundException::class);

        $ordemService->process(
            $this->customer,
            $this->item,
            "",
            $this->creditCard
        );
    }

    /**
     * @test
     */
    public function shouldSuccessfullyProcess()
    {
        $ordemService = new OrderService(
            $this->badWordsValidator,
            $this->paymentService,
            $this->ordemRepository,
            $this->fidelityProgramService
        );

        $this->customer
            ->method('isAllowedToOrder')
            ->willReturn(true);

        $this->item
            ->method('isAvailable')
            ->willReturn(true);

        $this->badWordsValidator
            ->method('hasBadWords')
            ->willReturn(false);

        $paymentTransaction = $this->createMock(PaymentTransaction::class);

        $this->paymentService
            ->method('pay')
            ->willReturn($paymentTransaction);

        $this->ordemRepository
            ->expects(self::once())
            ->method('save');

        $createdOrder = $ordemService->process(
            $this->customer,
            $this->item,
            "",
            $this->creditCard
        );

        $this->assertNotEmpty($createdOrder->getPaymentTransaction());
    }
}