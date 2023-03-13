<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comment')]
class CommentController extends AbstractController
{
    /**
     * add a new comment
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Article $article
     * @return Response
     */
    #[Route('/add/{slug}', name: 'app_add_comment')]
    public function index(Request $request, EntityManagerInterface $em, Article $article): Response
    {
        if(!$this->isGranted('ROLE_USER')) {
            return $this->render('home/addComment.html.twig');
        }

        $comment = new Comment();
        $comment
            ->setUser($this->getUser())
            ->setArticle($article);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('app_article_display', ['slug' => $article->getSlug()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('article/displayArticle.html.twig', [
            'article' => $article,
        ]);
    }
}
