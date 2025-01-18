<?php

declare(strict_types=1);

namespace App\Presenter\Entities;

use App\Domain\Entities\Expiration;
use App\Domain\Entities\Exposure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('expiration', EnumType::class, ['class' => Expiration::class])
            ->add('exposure', EnumType::class, ['class' => Exposure::class])
            ->add('burn', CheckboxType::class, [
                'label' => 'Burn after read',
                'required' => false,
                'empty_data' => false,
                'value' => true,
            ])
            ->add('cooking', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'      => PasteForm::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'task_item',
        ]);
    }
}
