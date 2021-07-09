<?php

namespace App\Service;

use App\Entity\Article;
use App\Repository\AuthorRepository;
use Symfony\Component\Security\Core\Security;

class ArticleCreator
{

    /**
     * @var \App\Repository\AuthorRepository
     */
    private $authorRepository;
    /**
     * @var \Symfony\Component\Security\Core\Security
     */
    private $security;

    public function __construct(
        AuthorRepository $authorRepository,
        Security $security
    ) {
        $this->authorRepository = $authorRepository;
        $this->security = $security;
    }

    public function createForCurrentUser(): Article
    {
        $author = $this->authorRepository->findOneBy([
            'authenticatedBy' => $this->security->getUser(),
        ]);

        if (!$author) {
            throw new \Exception('pas d\'auteur');
        }

        return (new Article())
            ->setCreatedAt(new \DateTimeImmutable())
            ->setWrittenBy($author)
        ;
    }
}