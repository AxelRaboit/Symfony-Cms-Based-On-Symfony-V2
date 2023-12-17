<?php

namespace App\Form\backend\admin\dashboard\advancedData\website;

use App\Entity\Website;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class WebsiteEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nom',
            ])
            ->add('domain', TextType::class, [
                'required' => true,
                'label' => 'Nom de domaine',
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Email',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[A-z0-9._%+-]+@[A-z0-9.-]+\.[A-z]{2,}$/',
                        'message' => 'L\'email n\'est pas valide.',
                    ]),
                ],
            ])
            ->add('hostname', TextType::class, [
                'required' => true,
                'label' => 'Nom d\'hôte',
            ])
            ->add('protocol', ChoiceType::class, [
                'choices' => [
                    'http://' => 'http://',
                    'https://' => 'https://',
                ],
                'required' => true,
                'label' => 'Protocole',
            ])
            ->add('port', IntegerType::class, [
                'required' => true,
                'label' => 'Port',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\d{1,4}$/',
                        'message' => 'Le port doit être un nombre composé de 1 à 4 chiffres',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Website::class,
        ]);
    }
}
