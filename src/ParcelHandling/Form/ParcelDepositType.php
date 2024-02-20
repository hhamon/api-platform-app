<?php

declare(strict_types=1);

namespace App\ParcelHandling\Form;

use App\Entity\ParcelLocker;
use App\ParcelHandling\Model\ParcelDeposit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ParcelDepositType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('parcelSerial', TextType::class, [
                'help' => 'Fill the 10 characters of the parcel unit',
                'attr' => [
                    'placeholder' => '097T410KSD',
                    'minlength' => '10',
                    'maxlength' => '10',
                    'pattern' => '[A-Z0-9]{10}',
                ],
            ])
            ->add('preferredLockerSize', ChoiceType::class, [
                'required' => false,
                'help' => 'Size must be greater than or equal the parcel unit size.',
                'choices' => ParcelLocker::SIZES,
            ])
            ->add('internalNotes', TextareaType::class, [
                'required' => false,
                'label' => 'Provide any useful information about the deposited parcel unit.',
                'attr' => [
                    'rows' => '5',
                    'cols' => '40',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Deposit this parcel',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ParcelDeposit::class,
        ]);
    }
}
