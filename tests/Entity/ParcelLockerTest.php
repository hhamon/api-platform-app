<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\LockerFacility;
use App\Entity\ParcelLocker;
use App\Entity\ParcelUnit;
use App\Entity\ParcelUnitDeposit;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\ClockAwareTrait;
use Symfony\Component\Clock\DatePoint;
use Symfony\Component\Clock\Test\ClockSensitiveTrait;

final class ParcelLockerTest extends TestCase
{
    use ClockSensitiveTrait;

    public function testAcceptParcelDepositSuccessfully(): void
    {
        $this->mockTime('2023-06-12 19:03:12');

        $facility = new LockerFacility('PARIS');
        $unit = new ParcelUnit('SERIAL_NUMBER', ParcelLocker::SIZE_LARGE, 'foo@example.com', false);

        $locker = new ParcelLocker($facility, 'SERIAL', ParcelLocker::SIZE_LARGE);

        $deposit = new ParcelUnitDeposit($unit, $locker, new DatePoint());

        $locker->acceptParcelDeposit($deposit, 'ABCDEF');

        $this->assertSame(ParcelLocker::STATE_IN_USE, $locker->getState());
        $this->assertSame('ABCDEF', $locker->getUnlockCode());
        $this->assertSame($deposit, $locker->getDeposit());
        $this->assertSame('2023-06-12 19:03:12', $locker->getDeposit()->getDepositedAt()->format('Y-m-d H:i:s'));
    }

    public function testAcceptParcelDepositFails(): void
    {
        $this->mockTime('2023-06-12 19:03:12');

        $facility = new LockerFacility('PARIS');
        $unit = new ParcelUnit('SERIAL_NUMBER', ParcelLocker::SIZE_LARGE, 'foo@example.com', false);

        $locker = new ParcelLocker($facility, 'SERIAL', ParcelLocker::SIZE_LARGE);

        $deposit = new ParcelUnitDeposit($unit, $locker, new DatePoint());

        $locker->acceptParcelDeposit($deposit, 'ABCDEF');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Locker is not available for use.');

        $locker->acceptParcelDeposit($deposit, 'ABCDEF');
    }
}
