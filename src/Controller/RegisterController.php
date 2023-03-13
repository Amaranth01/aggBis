<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/register')]
class RegisterController extends AbstractController
{
    #[Route('/', name: 'app_register')]
    public function register(Request $request, ParameterBagInterface $container, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response {
        if($this->getUser()) {
            return $this->render('home/addComment.html.twig', [
                'controller_name' => 'HomeController',
            ]);
        }
        $user = new User();
        $role = ["ROLE_USER"];
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            //Hashing password
            $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));

            //Get file for the profile picture
            $file = $form['image']->getData();
            $ext = $file->guessExtension();
            if(!$ext) {
                $ext = 'jpg';
            }
            //Move and rename a file
            $file->move($container->get('upload.directory'), uniqid() . "." . $ext);
            //register in database
            $user->setRoles($role);
            $em->persist($user);
            $em->flush();
        }
        return $this->render('register/register.html.twig', [
            'register_form' => $form->createView(),
        ]);
    }
}
