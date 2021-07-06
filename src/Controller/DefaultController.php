<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
        $article = new Article();

        $form = $this->createFormBuilder($article)
            ->add('title')
            ->add('body')
            ->add('publishedAt', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'required' => false,
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setCreatedAt(new \DateTimeImmutable());
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
        $form = $this->createFormBuilder($article, [
            'method' => 'PUT',
        ])
            ->add('title')
            ->add('body')
            ->add('publishedAt', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'required' => false,
            ])
            ->getForm()
        ;

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
}
