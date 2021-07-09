<?php

namespace App\EventListener;

use App\Entity\Article;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ArticlePersistListener
{
    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var \Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface
     */
    private $flashBag;

    public function __construct(
        MailerInterface $mailer,
        FlashBagInterface $flashBag
    ) {
        $this->mailer = $mailer;
        $this->flashBag = $flashBag;
    }

    public function postPersist(Article $article, LifecycleEventArgs $eventArgs)
    {
        $email = (new Email())
            ->to('recipient@example.com')
            ->from('sender@example.com')
            ->subject('Un nouvel article a été créé')
            ->text('le contenu au format texte')
            ->html('<p>Le contenu au format HTML</p>')
        ;

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $exception) {
            $this->flashBag->add('error', 'L\'envoi de l\'email a échoué');
        }
    }
}