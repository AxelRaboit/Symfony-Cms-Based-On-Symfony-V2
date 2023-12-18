<?php

namespace App\Service\Contact;

use App\Enum\DataEnum;
use App\Enum\WebsiteEnum;
use App\Manager\DataEnumManager;
use App\Service\Website\WebsiteService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

readonly class ContactService
{
    public function __construct(
        private Environment $twig,
        private DataEnumManager $dataEnumManager,
        private MailerInterface $mailer,
        private WebsiteService $websiteService,
    ) {
    }

    /**
     * Prepare data for sending an email.
     *
     * @param array<mixed> $data           the data submitted through the contact form
     * @param Request      $request        the request object containing client IP and user agent information
     * @param float|null   $recaptchaScore (optional) the score from the recaptcha validation
     *
     * @return array<mixed> the prepared data for sending an email
     */
    public function prepareDataForEmail(array $data, Request $request, float $recaptchaScore = null): array
    {
        $result = [
            'email' => $data['email'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'phone' => $data['phone'],
            'subject' => $data['subject'],
            'message' => $data['message'],
            'clientIp' => $request->getClientIp(),
            'userAgent' => $_SERVER['HTTP_USER_AGENT'],
        ];

        if (null !== $recaptchaScore) {
            $result['score'] = $recaptchaScore;
        }

        return $result;
    }

    /**
     * Sends the contact form.
     *
     * @param array<mixed> $data the data submitted through the contact form
     *
     * @throws \Exception|TransportExceptionInterface
     */
    public function sendContactEmail(array $data): bool
    {
        $website = $this->websiteService->getCurrentWebsite();
        /** @var string $template */
        $template = $this->dataEnumManager->getDataEnumValue(DataEnum::DATA_TEMPLATE_EMAIL_CONTACT_DEV_KEY);

        if (!$template) {
            throw new \Exception('Template not found');
        }

        if (!$website) {
            throw new \Exception('Website not found');
        }

        $email = (new Email())
            ->from($data['email'])
            ->to($website->getEmail() ?? WebsiteEnum::CONTACT_EMAIL_DEFAULT)
            ->subject($data['subject'])
            ->html($this->twig->render($template, ['contact' => $data])
            );

        $this->mailer->send($email);

        return true;
    }
}
