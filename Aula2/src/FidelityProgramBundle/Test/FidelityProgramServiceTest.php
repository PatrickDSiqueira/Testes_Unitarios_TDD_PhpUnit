<?php

namespace FidelityProgramBundle\Service;

use FidelityProgramBundle\Repository\PointsRepository;
use OrderBundle\Entity\Customer;
use PHPUnit\Framework\TestCase;

class FidelityProgramServiceTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSaveWhenReceivePoints()
    {

        $pointsRepository = $this->createMock(PointsRepository::class);
        $pointsRepository->expects($this->once())
            ->method('save');

        $pointCalculator = $this->createMock(PointsCalculator::class);
        $pointCalculator->method('calculatePointsToReceive')
            ->willReturn(20);

        $fidelityProgramService = new FidelityProgramService($pointsRepository, $pointCalculator);

        $customer = $this->createMock(Customer::class);
        $value = 20;

        $fidelityProgramService->addPoints($customer, $value);
    }

    /**
     * @test
     */
    public function shouldNotSaveWhenReceiveZeroPoints()
    {

        $pointsRepository = $this->createMock(PointsRepository::class);
        $pointsRepository->expects($this->never())
            ->method('save');

        $pointCalculator = $this->createMock(PointsCalculator::class);
        $pointCalculator->method('calculatePointsToReceive')
            ->willReturn(0);

        $fidelityProgramService = new FidelityProgramService($pointsRepository, $pointCalculator);

        $customer = $this->createMock(Customer::class);
        $value = 20;

        $fidelityProgramService->addPoints($customer, $value);
    }
}