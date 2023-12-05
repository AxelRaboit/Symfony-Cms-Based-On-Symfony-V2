<?php

namespace App\Form\backend\admin\dashboard\advancedData\dataEnum;

use App\Entity\DataEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class DataEnumEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nom',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^DATA_[A-Z_]*$/',
                        'message' => 'Le nom doit commencer par "DATA_" et ne contenir que des lettres majuscules et des underscores.',
                    ]),
                ],
            ])
            ->add('category', TextType::class, [
                'required' => false,
                'label' => 'Catégorie'
            ])
            ->add('value', TextType::class, [
                'required' => false,
                'label' => 'Valeur'
            ])
            ->add('devKey', IntegerType::class, [
                'required' => true,
                'label' => 'Clé de dev'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DataEnum::class,
        ]);
    }
}
