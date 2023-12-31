<?php

namespace App\Form\backend\admin\dashboard\userBackend\message;

use App\Entity\BackendMessage;
use App\Entity\UserBackend;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class BackendMessageType extends AbstractType
{
    public function __construct(private readonly Security $security)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        if (!$user instanceof UserBackend) {
            throw new \LogicException('User must be logged in.');
        }

        $email = $user->getEmail();

        $builder
            ->add('subject', TextType::class, [
                'label' => 'Sujet',
            ])
            ->add('sender', TextType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Expéditeur',
                'data' => $email,
                'attr' => ['readonly' => true],
            ])
            ->add('receiver', EntityType::class, [
                'class' => UserBackend::class,
                'required' => true,
                'placeholder' => 'Choisir un destinataire',
                'choice_label' => 'email',
                'label' => 'Destinataire',
                'query_builder' => function (EntityRepository $er) use ($email) {
                    return $er->createQueryBuilder('u')
                        ->where('u.email != :email')
                        ->setParameter('email', $email);
                },
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez choisir un destinataire',
                    ]),
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => ['rows' => 10],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un message',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BackendMessage::class,
        ]);
    }
}
