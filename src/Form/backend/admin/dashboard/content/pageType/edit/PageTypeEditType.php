<?php

namespace App\Form\backend\admin\dashboard\content\pageType\edit;

use App\Entity\PageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class PageTypeEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => true,
            ])
            ->add('template', TextType::class, [
                'label' => 'Template',
                'required' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => '/html\.twig$/',
                        'message' => 'Le nom du fichier doit se terminer par "html.twig".',
                    ]),
                ],
            ])
            ->add('urlPrefix', TextType::class, [
                'label' => 'Préfixe URL',
                'required' => true,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\/[a-z-]*$/',
                        'message' => 'Le pefix url doit commencer par un "/" et ne contenir que des lettres minuscules et des tirets.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PageType::class,
        ]);
    }
}
