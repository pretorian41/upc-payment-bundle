<?php

declare(strict_types=1);

namespace UPCPaymentBundle\Form;

use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('Version', HiddenType::class, []);
        $builder->add('MerchantID', HiddenType::class, []);
        $builder->add('TerminalID', HiddenType::class, []);
        $builder->add('TotalAmount', HiddenType::class, []);
        $builder->add('Currency', HiddenType::class, []);
        $builder->add('locale', HiddenType::class, []);
        $builder->add('PurchaseTime', HiddenType::class, ['data' => (int)(new DateTime())->format('ymdHis')]);
        $builder->add('OrderID', HiddenType::class, []);
        $builder->add('PurchaseDesc', HiddenType::class, []);
        $builder->add('Signature', HiddenType::class, []);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
