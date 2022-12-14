<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/article/ajouter', name: 'ajout_article')]
    #[Route('/article/{id}/edition', requirements: ['id' => '\d+'], name: 'edit_article', methods: ['GET', 'POST'])]
    public function ajouter(Article $article = null, Request $request, EntityManagerInterface $manager)
    {
        if (!$article) {
            $article = new Article();
        }

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('brouillon')->isClicked()) {
                $article->setState('brouillon');
            } else {
                $article->setState('a publier');
            }

            if (!$article->getId()) {
                $manager->persist($article);
            }
            $manager->flush();

            return $this->redirectToRoute('liste_articles');
        }

        return $this->render(
            'default/ajouter.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    #[Route('article/brouillon', name: 'brouillon_article')]
    public function brouillon_article(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findBy(['state' => 'brouillon']);
        return $this->render('default/index.html.twig', ['articles' => $articles, 'brouillon' => true]);
    }
}
