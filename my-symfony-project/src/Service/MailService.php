<?php

namespace App\Service;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class MailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(
        string $from,
        string $subject,
        string $html
    ): void
    {
        $email = (new Email())
                ->from($from)
                ->to('admin@symrecipe.com')
                ->subject($subject)
                ->html($html);

        $this->mailer->send($email);
    }
}