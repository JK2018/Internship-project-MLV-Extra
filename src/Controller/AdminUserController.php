<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminUserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/users", name="admin_user_index")
     * 
     */
    public function index(UserRepository $repo)
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $repo->findAll(),
        ]);
    }



/**
     * to edit an existing user
     * 
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/user/{id}/edit", name="admin_user_edit")
     *
     * @return Response
     */
    public function edit(ObjectManager $manager, User $user, Request $request ){

        $form = $this->createForm(AdminUserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $manager->persist($user);
            $manager->flush();
        
        $this->addFlash(
            'success',
            "Le profil utilisateur n° {$booking->getId()} a bien été modifiée !"
        );
        return $this->redirectToRoute("admin_user_index");
    }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }


    /**
     * delete a user
     * @Route("/admin/user/{id}/delete", name="admin_user_delete")
     * @IsGranted("ROLE_ADMIN")
     *
     * @return Response
     */
    public function delete(User $user, ObjectManager $manager){

        $manager->remove($user);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le profil utilisateur a bien été supprimé !"
        );

        return $this->redirectToRoute("admin_user_index");
    }
}

