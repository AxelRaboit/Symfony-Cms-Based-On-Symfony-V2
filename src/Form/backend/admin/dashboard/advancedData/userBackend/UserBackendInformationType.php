<?php

namespace App\Form\backend\admin\dashboard\advancedData\userBackend;

use App\Entity\UserBackendInformation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserBackendInformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pictureProfileFile', VichImageType::class, [
                'required' => true,
                'allow_delete' => false,
                'download_uri' => false,
                'label' => 'Photo de profil',
                'image_uri' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserBackendInformation::class,
        ]);
    }
}
