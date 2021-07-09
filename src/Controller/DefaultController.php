<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Author;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", methods="GET")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("")
     * @Cache(
     *     expires="+1 hour",
     *     public=true,
     *     maxage="3600",
     *     smaxage="1800",
     *     staleWhileRevalidate="120"
     * )
     */
    public function index(ArticleRepository $repository): Response
    {
        return $this->render('default/index.html.twig', [
            'articles' => $repository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     */
    public function show(Article $article): Response
    {
        $response = $this->render('default/show.html.twig', [
            'article' => $article,
        ]);

        $response->setMaxAge(1800)
            ->setExpires(new \DateTimeImmutable('tomorrow'))
            ->setPublic()
            ->setLastModified($article->getPublishedAt())
            ->headers->addCacheControlDirective('must-revalidate')
        ;

        return $response;
    }

    /**
     * @Route("/{id}/edit", methods={"GET", "PUT"})
     */
    public function edit(
        Article $article,
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $form = $this->createForm(ArticleType::class, $article, [
            'method' => 'PUT',
            'with_publishedAt_field' => is_null($article->getPublishedAt()),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash('success', 'Vous avez modifié votre article');

            return $this->redirectToRoute('app_default_show', [
                'id' => $article->getId(),
            ]);
        }

        return $this->renderForm('default/edit.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route(
     *     "/list/{month}",
     *     requirements={"month": "\d{4}-\d{2}"},
     *     defaults={"month": "this month"}
     * )
     */
    public function indexByMonth(
        \DateTimeImmutable $month,
        ArticleRepository $repository
    ): Response {
        $articles = $repository->findByMonth($month);

        return $this->render('default/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/last/{id}")
     * @Cache(expires="+1 hour")
     */
    public function indexLast(Author $author, ArticleRepository $repository): Response
    {
        $articles = $repository->findBy(
            ['writtenBy' => $author],
            ['publishedAt' => 'DESC']
        );

        return $this->render('default/index_last.html.twig', [
            'articles' => $articles,
        ]);
    }
}
