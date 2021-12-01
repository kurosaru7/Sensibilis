<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;
use App\Repository\ThemeRepository;
use App\Entity\Article;
class BiblioController extends AbstractController
{
    /**
     * @Route("/bibliotheque", name="biblio")
     */
    public function index(ArticleRepository $articleRepository, ThemeRepository $themeRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $validTheme = $themeRepository->findBy(array("active" => 1));

        $articles = $articleRepository->findAll();

        foreach($articles as $article)
        {
            if(in_array($article->getTheme(), $validTheme))
            $print[] = $article;
        }

        if(!isset($print))
        {
            $print = false;
        }
        return $this->render('biblio/index.html.twig', [
            'articles' => $print
        ]);
    }

    /**
     * @Route("/article/{article}", name="showArticle")
     */
    public function show(Article $article,ArticleRepository $articleRepository)
    {   
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $videolinks = explode(',', $article->getVideoLinks());
        $links = explode(',', $article->getOtherLinks());

        return $this->render('biblio/show.html.twig', [
            'article' => $article,
            'videolinks' => $videolinks,
            'links' => $links
        ]);
    }
}
