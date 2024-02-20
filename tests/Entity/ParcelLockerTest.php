<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\LockerFacility;
use App\Entity\ParcelLocker;
use App\Entity\ParcelUnit;
use App\Entity\ParcelUnitDeposit;
use PHPUnit\Framework\TestCase;

final class ParcelLockerTest extends TestCase
{
    public function testAcceptParcelDepositSuccessfully(): void
    {
        $facility = new LockerFacility('PARIS');
        $unit = new ParcelUnit('SERIAL_NUMBER', ParcelLocker::SIZE_LARGE, 'foo@example.com', false);

        $locker = new ParcelLocker($facility, 'SERIAL', ParcelLocker::SIZE_LARGE);

        $deposit = new ParcelUnitDeposit($unit, $locker);

        $locker->acceptParcelDeposit($deposit, 'ABCDEF');

        $this->assertSame(ParcelLocker::STATE_IN_USE, $locker->getState());
        $this->assertSame('ABCDEF', $locker->getUnlockCode());
        $this->assertSame($deposit, $locker->getDeposit());
    }

    public function testAcceptParcelDepositFails(): void
    {
        $facility = new LockerFacility('PARIS');
        $unit = new ParcelUnit('SERIAL_NUMBER', ParcelLocker::SIZE_LARGE, 'foo@example.com', false);

        $locker = new ParcelLocker($facility, 'SERIAL', ParcelLocker::SIZE_LARGE);

        $deposit = new ParcelUnitDeposit($unit, $locker);

        $locker->acceptParcelDeposit($deposit, 'ABCDEF');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Locker is not available for use.');

        $locker->acceptParcelDeposit($deposit, 'ABCDEF');
    }
}
