<?php

declare(strict_types=1);

namespace App\ParcelHandling;

use App\Entity\ParcelUnitDeposit;
use App\ParcelHandling\Event\ParcelUnitDepositedEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ParcelHandlingMailer implements EventSubscriberInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ParcelUnitDepositedEvent::class => ['onParcelUnitDeposited', 10],
        ];
    }

    public function onParcelUnitDeposited(ParcelUnitDepositedEvent $event): void
    {
        $locker = $event->getParcelLocker();

        if (($parcelUnitDeposit = $locker->getDeposit()) instanceof ParcelUnitDeposit) {
            $this->sendAvailableParcelPickupEmail($parcelUnitDeposit);
        }
    }

    private function sendAvailableParcelPickupEmail(ParcelUnitDeposit $parcelUnitDeposit): void
    {
        $email = (new TemplatedEmail())
            ->to($parcelUnitDeposit->getParcel()->getCustomerEmail())
            ->subject($this->translator->trans('email.subject.parcel_deposited_at_facility', [
                '{parcel_serial}' => $parcelUnitDeposit->getParcel()->getSerial(),
            ]))
            ->htmlTemplate('emails/parcel_deposited.html.twig')
            ->context(['parcel_deposit' => $parcelUnitDeposit]);

        $this->mailer->send($email);
    }
}
