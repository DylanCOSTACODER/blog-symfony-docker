<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Service\VerificationComment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends AbstractController
{
    // Liste l'ensemble des articles
    #[Route('/', name: 'liste_articles',  methods: ['GET'])]
    public function listeArticles(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy([
            'state' => 'publie'
        ]);
        return $this->render('default/index.html.twig', ['articles' => $articles, 'brouillon' => false]);
    }

    // Afficher un article en particulier
    #[Route('/{id}', name: 'vue_article', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function vueArticle(Article $article, Request $request, EntityManagerInterface $manager, VerificationComment $verificationComment): Response
    {
        $session = $request->getSession();
        $comment = new Comment();
        $comment->setArticle($article);
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!$verificationComment->commentNonAuthorise($comment)) {
                $manager->persist($comment);
                $manager->flush();
                return $this->redirectToRoute('vue_article', ['id' => $article->getId()]);
            } else {
                $session->getFlashBag()->add("danger", "Le commentaire contient un mot interdit");
            }
        }

        return $this->render(
            'default/vue.html.twig',
            [
                'article' => $article,
                'form' => $form->createView()
            ]
        );
    }

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
                $article->setState('publie');
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
