<?php

namespace App\Services;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    /**
     * @var MailerInterface $mailer
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(string $to, string $subject, string $text)
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->replyTo('fabien@example.com')
            ->to($to)
            ->subject($subject)
            ->text($text)
        ;

        //->to('you@example.com')
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->subject('Time for Symfony Mailer!')
        //->text('Sending emails is fun again!')
        //->html('<p>See Twig integration for better HTML integration!</p>');

        $this->mailer->send($email);

    }
}