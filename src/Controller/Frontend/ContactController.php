<?php

namespace App\Controller\Frontend;

use App\Entity\Page;
use App\Enum\DataEnum;
use App\Enum\GoogleEnum;
use App\Form\frontend\ContactType;
use App\Manager\DataEnumManager;
use App\Repository\PageRepository;
use App\Service\Contact\ContactService;
use App\Service\Google\GoogleCaptchaService;
use App\Service\Page\PageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    public function __construct(
        private readonly DataEnumManager $dataEnumManager,
        private readonly GoogleCaptchaService $googleCaptchaService,
        private readonly ContactService $contactService,
        private readonly PageService $pageService,
        private readonly PageRepository $pageRepository,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    #[Route('/contactez-nous', name: 'app_contact', priority: 1)]
    public function index(Request $request): Response
    {
        /** @var Page|null $page */
        $page = $this->pageRepository->getPageFromDataDevKey(DataEnum::DATA_PAGE_CONTACT_DEV_KEY);

        /** @var string|null $recaptchaPublicKey */
        $recaptchaPublicKey = $this->dataEnumManager->getDataEnumValue(DataEnum::DATA_RECAPTCHA_PUBLIC_DEV_KEY);
        /** @var string|null $recaptchaSecretKey */
        $recaptchaSecretKey = $this->dataEnumManager->getDataEnumValue(DataEnum::DATA_RECAPTCHA_SECRET_DEV_KEY);

        if (null === $page) {
            throw $this->createNotFoundException('Page not found');
        }

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $captcha = $form->get('recaptcha')->getData();

                if (empty($captcha && null !== $recaptchaPublicKey && null !== $recaptchaSecretKey)) {
                    return $this->redirectToRoute($request->attributes->get('_route'));
                }

                $data = $form->getData();

                if (null !== $recaptchaPublicKey && '' !== $recaptchaPublicKey
                    && null !== $recaptchaSecretKey && '' !== $recaptchaSecretKey) {
                    $response = $this->googleCaptchaService->verify($captcha);
                    $recaptchaScore = $response['score'];

                    $data = $this->contactService->prepareDataForEmail($data, $request, $recaptchaScore);

                    if ($response['success'] && $recaptchaScore > GoogleEnum::RECAPTCHA_SCORE_LIMIT) {
                        $this->contactService->sendContactEmail($data);
                    }
                } else {
                    $this->contactService->sendContactEmail($data);
                }

                $this->addFlash('success', 'Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.');

                return $this->redirectToRoute($request->attributes->get('_route'));
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur s\'est produite lors de l\'envoi du message. Veuillez réessayer ultérieurement.');
            }
        }

        $elements = $this->pageService->getPageElements($page);

        $elements['form'] = $form->createView();

        return $this->render($elements['template'], $elements);
    }
}
