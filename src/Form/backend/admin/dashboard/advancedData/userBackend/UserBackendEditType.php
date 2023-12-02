<?php

namespace App\Form\backend\admin\dashboard\advancedData\userBackend;

use App\Entity\UserBackend;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserBackendEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Email',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un email',
                    ]),
                    new Email([
                        'message' => 'Veuillez saisir un email valide',
                    ]),
                ],
            ])
            ->add('username', TextType::class, [
                'required' => false,
                'label' => 'Nom d\'utilisateur',
            ])
            ->add('password', UserBackendPasswordEditType::class, [
                'mapped' => false,
                'label' => false,
            ])
            ->add('information', UserBackendInformationType::class, [
                'required' => false,
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserBackend::class,
        ]);
    }
}
