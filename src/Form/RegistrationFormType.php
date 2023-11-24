<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\UserBackend;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function __construct(private readonly CityRepository $cityRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('country', EntityType::Class, [
                'constraints' => [
                    new NotBlank(['message' => 'Please select a country']),
                ],
                'placeholder' => 'Select a country',
                'class' => Country::class,
                'choice_label' => 'name',
                'query_builder' => function (CountryRepository $countryRepository) {
                    return $countryRepository->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
            ]);

        $formModifier = function (FormInterface $form, Country $country = null) {
            $cities = $country == null ? [] : $this->cityRepository->findByCountry($country);

            $form->add('city', EntityType::Class, [
                'placeholder' => 'Select a city' . ($country === null ? '' : ' for ' . $country->getName()),
                'required' => true,
                'class' => City::class,
                'disabled' => $country == null,
                'choice_label' => 'name',
                'choices' => $cities,
                'attr' => [
                    'class' => $country === null ? 'hidden' : '',
                ],]);
        };

        // This is used to set the initial value of the city field
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();
                $formModifier($event->getForm(), $data->getCountry());
            });

        // This is used to update the city field when the country field is changed
        /* POST_SUBMIT means that this event is triggered after the form is submitted
        (the form is only equal to the field that triggered the event, not the whole form) */
        $builder->get('country')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $country = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $country);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserBackend::class,
        ]);
    }
}
