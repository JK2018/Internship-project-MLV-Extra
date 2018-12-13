<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends Controller
{





    /**
     * leads to the login form.
     * symfony deals with logging in , check security.yaml
     * @Route("/login", name="account_login")
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        
        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null, // transfer 'hasError' param to twig if $error isn't empty.
            'username' => $username // sets last username as default in email feild
        ]);
    }






    /**
     * gives user ability to logout.
     * @Route("/logout", name="account_logout")
     *
     * @return void
     */
    public function logout(){
        //symfony deals with logging out , check security.yaml
    }








    /**
     * leads to registration form
     * @Route("/register", name="account_register")
     * @return Response
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder){
        $user = new User();

        //to generate form
        $form = $this->createForm(RegistrationType::class, $user);

        //to persist user data while hashing the pw
        $form->handleRequest($request); 
        if($form->isSubmitted() && $form->isValid()){ 
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "Votre compte a bien été créé, vous pouvez maintenant vous connecter !");
            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
