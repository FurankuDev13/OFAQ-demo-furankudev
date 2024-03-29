<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/** 
 *  @Route("", name="user_") 
*/
class UserController extends AbstractController
{
    /**
     * @Route("/signin", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, RoleRepository $roleRepo, \Swift_Mailer $mailer)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encodedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encodedPassword);
            $user->setRole($roleRepo->findOneBy(['name' => 'Utilisateur']));
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                $user->getUsername() . ', votre compte a été créé, vous pouvez vous connecter !'
            );

            $message = (new \Swift_Message("Bienvenue chez o'Faq"))
                ->setFrom('sith13160@gmail.com')
                ->setTo('sith13160@gmail.com', $user->getEmail())
                ->setBody(
                    $this->renderView(
                        // templates/emails/registration.html.twig
                        'emails/notification.html.twig',
                        ['username' => $user->getUsername()]
                    ),
                    'text/html'
                );

                $mailer->send($message);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/signin.html.twig', [
            'page_title' => 'Inscription',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/account", name="show", methods={"GET"})
     */
    public function show()
    {
        return $this->render('user/account.html.twig', [
            'page_title' => 'Profil',
        ]);
    }

    /**
     * @Route("/account/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, RoleRepository $roleRepo, UserRepository $userRepo)
    {
        $user = $userRepo->find($this->getUser()->getId());
        $oldPassword = $user->getPassword();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($user->getPassword() == ''){
                $encodedPassword = $oldPassword;
            } else {
                $encodedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
            }
            $user->setPassword($encodedPassword);
            $entityManager->flush();

            $this->addFlash(
                'info',
                $user->getUsername() . ', votre profil a été mis à jour !'
            );

            return $this->redirectToRoute('user_show');
        }

        return $this->render('user/edit.html.twig', [
            'page_title' => 'Mettre à jour le profil',
            'form' => $form->createView()
        ]);
    }
}
