<?php

namespace App\EventListener;

use App\Entity\Article;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;

class ArticlePersistListener
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function postPersist(Article $article, LifecycleEventArgs $eventArgs)
    {
        $this->logger->info('Nouvel article : '.$article->getTitle());
    }
}