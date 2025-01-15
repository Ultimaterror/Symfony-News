<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/articles')]
class ArticleController extends AbstractController
{

    #[Route('/', name: 'article_list')]
    public function list(ArticleRepository $articleRepository): Response
    {
        // Pagination
        // $page = $request->query->getInt('page');

        // 1 - Je récupère les articles en discutant avec ma couche de service
        $articles = $articleRepository->findAll();

        // 2 - Je transmets les articles à la vue que je souhaite afficher
        return $this->render('article/list.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/{id}', name: "article_item")]
    public function item(Article $article): Response
    {
        return $this->render('article/item.html.twig', [
            'article' => $article
        ]);
    }
}
