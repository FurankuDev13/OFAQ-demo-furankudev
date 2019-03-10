<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/** 
 *  @Route("/backend/user", name="backend_user_") 
*/
class UserController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(UserRepository $userRepo)
    {
        $users = $userRepo->findAll();

        return $this->render('backend/user/index.html.twig', [
            'page_title' => 'Administration - Liste des utilisateurs',
            'users' => $users
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(User $user)
    {
        return $this->render('backend/user/show.html.twig', [
            'page_title' => 'Profil - ' . $user->getUsername(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/{id}/editRole", name="editRole", methods={"PATCH"})
     */
    public function editRole(User $user, RoleRepository $roleRepo, EntityManagerInterface $entityManager)
    {
        $moderatorRole = $roleRepo->findOneByName('Modérateur');
        $userRole = $roleRepo->findOneByName('Utilisateur');

        if ($user->getRole() == $userRole) {
            $user->setRole($moderatorRole);
        } elseif ($user->getRole()  == $moderatorRole) {
            $user->setRole($userRole);
        } 

        $entityManager->flush();
        
        return $this->redirectToRoute('backend_user_index');
    }
}
