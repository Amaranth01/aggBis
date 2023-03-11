<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article')]
    public function index(ArticleRepository $articleRepo): Response {
        return $this->render('home/index.html.twig', [
            'articles' => $articleRepo->findAll(),
        ]);
    }
    #[Route('/add', name: 'app_article_add')]
    public function articleAdd(Request $request, EntityManagerInterface $em, ParameterBagInterface $container, SluggerInterface $slugger): Response
    {
        if(!$this->isGranted('ROLE_WRITER')) {
            return $this->render('home/index.html.twig');
        }

        $user = $this->getUser();
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            //Get file for the profile picture
            $file = $form['image']->getData();
            $ext = $file->guessExtension();
            if(!$ext) {
                $ext = 'jpg';
            }
            //Move and rename a file
            $file->move($container->get('upload.directory'), uniqid() . "." . $ext);
            $article->setUser($user);
            $article->setSlug(strtolower($slugger->slug($form['title']->getData())));
            $em->persist($article);
            $em->flush();
        }
        return $this->render('article/addArticle.html.twig', [
            'article_form' => $form->createView(),
        ]);
    }
    #[Route('/{slug}', name: 'app_article_display', methods: ['GET'])]
    public function displayArticle(Article $article) : Response {
        return $this->render('article/displayArticle.html.twig', [
           'article' => $article,
        ]);
    }
}
