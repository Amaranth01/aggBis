<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function articleAdd(Article $article, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $published = $form->get('save')->isClicked();
            $article->setIsPublished($published);
        }
        return $this->render('article/addArticle.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }
}
