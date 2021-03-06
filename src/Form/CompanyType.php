<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, ['empty_data' => ''])
            ->add('username', TextType::class, ['empty_data' => ''])
            ->add('plainPassword',TextType::class, [
                'empty_data' => '',
                'constraints' => [
                    new NotBlank(['message' => 'plainPassword is missing or empty'])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
            'allow_extra_fields' => true,
            'csrf_protection'    => false,
        ]);
    }
}
