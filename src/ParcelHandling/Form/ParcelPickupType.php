<?php

declare(strict_types=1);

namespace App\ParcelHandling\Form;

use App\ParcelHandling\Model\ParcelPickup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ParcelPickupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('parcelSerial', TextType::class, [
                'label' => 'Parcel unit serial',
                'help' => 'Fill the 10 characters of the parcel unit',
                'attr' => [
                    'placeholder' => '097T410KSD',
                    'minlength' => '10',
                    'maxlength' => '10',
                    'pattern' => '[A-Z0-9]{10}',
                ],
            ])
            ->add('unlockCode', TextType::class, [
                'label' => 'Unlock code',
                'help' => 'Fill the 6 unlock code characters',
                'attr' => [
                    'placeholder' => 'FE36Q2',
                    'minlength' => '6',
                    'maxlength' => '6',
                    'pattern' => '[A-F0-9]{6}',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Pickup my parcel',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ParcelPickup::class,
        ]);
    }
}
