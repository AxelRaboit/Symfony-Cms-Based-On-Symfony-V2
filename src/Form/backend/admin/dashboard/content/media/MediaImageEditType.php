<?php

namespace App\Form\backend\admin\dashboard\content\media;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaImageEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom',
                'required' => false,
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'required' => false,
            ])
            ->add('category', TextType::class, [
                'label' => 'Catégorie',
                'required' => false,
            ])
            ->add('alt', TextType::class, [
                'label' => 'Texte alternatif',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
