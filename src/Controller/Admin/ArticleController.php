<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", methods="GET")
 */
class ArticleController extends AbstractController
{

    /**
     * @Route("/new", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(
        Request $request,
        EntityManagerInterface $manager
    ): Response {

        // gestion du contrôle d'accès personnalisée
        $user = $this->getUser();
        if (!$user || !$this->isGranted('ROLE_ADMIN', $user)) {
            throw $this->createAccessDeniedException();
        }

        // ou version raccourcie
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

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
     * @Route("/{id}/remove", methods={"GET", "DELETE"})
     */
    public function remove(
        Article $article,
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $user = $this->getUser();

        if ($user !== $article->getWrittenBy()->getAuthenticatedBy()) {
            throw $this->createAccessDeniedException(
                'controller: vous ne pouvez supprimer que vos propres articles'
            );
        }

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
}