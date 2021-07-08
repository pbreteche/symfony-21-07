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
        return $this->render('default/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/new", methods={"GET", "POST"})
     */
    public function new(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $article = (new Article())
            ->setCreatedAt(new \DateTimeImmutable())
        ;

        $form = $this->createForm(ArticleType::class, $article, [
            'validation_groups' => ['Default', 'create'],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($article);
            $manager->flush();

            $this->addFlash('success', 'Vous avez créé un nouvel article');

            return $this->redirectToRoute('app_default_show', [
                'id' => $article->getId(),
            ]);
        }

        return $this->renderForm('default/new.html.twig', [
            'form' => $form,
        ]);
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
     * @Route("/{id}/remove", methods={"GET", "DELETE"})
     */
    public function remove(
        Article $article,
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $token = $request->request->get('token');
        if (
            'DELETE' === $request->getMethod() &&
            $this->isCsrfTokenValid('delete-article', $token)
        ) {
            $manager->remove($article);
            $manager->flush();

            $this->addFlash('success', 'Vous avez supprimez l\'article');

            return $this->redirectToRoute('app_default_index');
        }

        return $this->render('default/remove.html.twig', [
            'article' => $article,
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
