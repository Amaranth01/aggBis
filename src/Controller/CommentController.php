<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
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
            return $this->render('home/index.html.twig');
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
            return $this->redirectToRoute('app_article_display', ['slug' => $article->getSlug()]);
        }
            return $this->render('comment/addComment.html.twig', [
            'comment_form' => $form->createView(),
        ]);
    }

    #[Route('/list', name: 'app_comment_list')]
    public function listComment(CommentRepository $commentRepo): Response {
        return $this->render('comment/listComment.html.twig', [
            'comments' => $commentRepo->findAll(),
        ]);
    }

    #[Route('/edit/{id}', name: 'app_comment_edit')]
    public function editComment(Request $request, Comment $comment, EntityManagerInterface $em): Response {
        if(!$this->isGranted('ROLE_MODO')) {
            return $this->render('home/index.html.twig');
        }
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
        }

        return $this->render('comment/listComment.html.twig', [
            'comments' => $comment,
            'comment_edit_form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name : 'app_comment_delete')]
    public function deleteComment(Comment $comment, EntityManagerInterface $em, CommentRepository $commentRepo): Response {
        if(!$this->isGranted('ROLE_MODO')) {
            return $this->render('home/index.html.twig');
        }
        $em->remove($comment);
        $em->flush();

        return $this->render('comment/listComment.html.twig', [
           'comments' => $commentRepo->findAll(),
        ]);
    }
}
