<?php

namespace App\Form\backend\admin\dashboard\content\page;

use App\Entity\Page;
use App\Enum\PageStateEnum;
use App\Repository\PageRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class PageEditType extends AbstractType
{
    public function __construct(private readonly PageRepository $pageRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentObjectId = null;

        if ($options['data'] && $options['data']->getId()) {
            $currentObjectId = $options['data']->getId();
        }

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la page',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un nom',
                    ]),
                ],
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre de la page',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un titre',
                    ]),
                ],
            ])
            ->add('template', TextType::class, [
                'label' => 'Template de la page',
                'required' => false,
            ])
            ->add('contentPrimary', TextareaType::class, [
                'label' => 'Contenu principal',
                'required' => false,
                'attr' => ['rows' => 7],
            ])
            ->add('contentSecondary', TextareaType::class, [
                'label' => 'Contenu secondaire',
                'required' => false,
                'attr' => ['rows' => 7],
            ])
            ->add('contentTertiary', TextareaType::class, [
                'label' => 'Contenu tertiaire',
                'required' => false,
                'attr' => ['rows' => 7],
            ])
            ->add('contentQuaternary', TextareaType::class, [
                'label' => 'Contenu quaternaire',
                'required' => false,
                'attr' => ['rows' => 7],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la page',
                'required' => false,
            ])
            ->add('devCodeRouteName', TextType::class, [
                'label' => 'Nom de la route',
                'required' => false,
            ])
            ->add('ctaTitle', TextType::class, [
                'label' => 'Titre du CTA',
                'required' => false,
            ])
            ->add('ctaText', TextType::class, [
                'label' => 'Texte du CTA',
                'required' => false,
            ])
            ->add('ctaUrl', TextType::class, [
                'label' => 'URL du CTA',
                'required' => false,
            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug de la page',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un slug',
                    ]),
                    new Callback(function ($slug, ExecutionContextInterface $context) use ($currentObjectId) {
                        $this->validateSlug($slug, $context, $currentObjectId);
                    }),
                ],
            ])
            ->add('pageType', EntityType::class, [
                'class' => 'App\Entity\PageType',
                'choice_label' => 'name',
                'label' => 'Type de page',
                'placeholder' => 'Sélectionner un type de page',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner un type de page',
                    ]),
                ],
            ])
            ->add('parent', EntityType::class, [
                'class' => 'App\Entity\Page',
                'choice_label' => 'name',
                'label' => 'Page parent',
                'placeholder' => 'Sélectionner une page parent',
                'required' => false,
            ])
            ->add('website', EntityType::class, [
                'class' => 'App\Entity\Website',
                'choice_label' => 'name',
                'label' => 'Site',
                'placeholder' => 'Sélectionner un site',
                'required' => true,
            ])
            ->add('canonicalUrl', TextType::class, [
                'label' => 'URL canonique',
                'required' => false,
            ])
            ->add('metaTitle', TextType::class, [
                'label' => 'Meta title',
                'required' => false,
            ])
            ->add('metaDescription', TextType::class, [
                'label' => 'Meta description',
                'required' => false,
            ])
            ->add('weight', TextType::class, [
                'label' => 'Poids',
                'required' => true,
            ])
            ->add('state', ChoiceType::class, [
                'label' => 'Etat',
                'required' => true,
                'choices' => [
                    'Brouillon' => PageStateEnum::DRAFT,
                    'Publier' => PageStateEnum::PUBLISHED,
                ],
            ])
            ->add('visibleForBackendActions', CheckboxType::class, [
                'label' => 'Visible pour les actions du backoffice',
                'required' => false,
            ])
            ->add('devKey', IntegerType::class, [
                'label' => 'Clé de dev',
                'required' => true,
            ])
            ->add('displayType', ChoiceType::class, [
                'label' => 'Type d\'affichage',
                'required' => true,
                'choices' => [
                    'Détail' => 'detail',
                    'Liste' => 'list',
                ],
            ]);
    }

    public function validateSlug(string $slug, ExecutionContextInterface $context, ?int $currentObjectId): void
    {
        $existingPage = $this->pageRepository->findOneBy(['slug' => $slug]);

        if ($existingPage && $existingPage->getId() != $currentObjectId) {
            $context->buildViolation('Ce slug existe déjà.')
                ->atPath('slug')
                ->addViolation();
        }

        if ('/' !== $slug && str_starts_with($slug, '/')) {
            $context->buildViolation('Le slug ne doit pas commencer par un slash.')
                ->atPath('slug')
                ->addViolation();
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
    }
}
