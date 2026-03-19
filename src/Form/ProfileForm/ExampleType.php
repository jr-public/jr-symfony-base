<?php

namespace App\Form\ProfileForm;

use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ExampleType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'property_path' => 'data[firstName]', 
                'constraints' => [
                    new NotBlank(message: 'Please enter your first name'),
                    new Length(max: 255),
                ],
            ])
            ->add('lastName', TextType::class, [
                'property_path' => 'data[lastName]',
                'constraints' => [
                    new NotBlank(message: 'Please enter your last name'),
                    new Length(max: 255),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}