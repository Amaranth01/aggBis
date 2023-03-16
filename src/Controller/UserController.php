<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
//    Route for the writers
    #[Route('/writer-space', name: 'writer_space')]
    public function writerSpace(): Response {
          //Secured for access
        if($this->getUser()->getRoles() != 'ROLE_WRITER') {
            $this->render('home/index.html.twig', [
                'controller_name' => 'HomeController',
            ]);
        }
        return $this->render('user/writer.html.twig');
    }

    //    Route for the moderators
    #[Route('/modo-space', name: 'modo_space')]
    public function modoSpace(): Response {
        //Secured for access
        if($this->getUser()->getRoles() != 'ROLE_MODO') {
            $this->render('home/index.html.twig', [
                'controller_name' => 'HomeController',
            ]);
        }
        return $this->render('user/modo.html.twig');
    }
}
