<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\UpdatePassword;
use App\Form\RegistrationType;
use App\Form\UpdatePasswordType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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




    /**
     * leads to the profile editing form
     * @Route("/account/profile", name="account_profile")
     *
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function profile(Request $request, ObjectManager $manager){

        $user = $this->getUser(); //to get user thats currently logged in.
        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "Les modifications ont bien été apportés à votre profil !");
        }

        return $this->render('account/profile.html.twig', [
            'form' => $form->createView()
            ]);
    }






    /**
     * leads to PW update form
     * 
     * @Route("/account/password-update", name="account_password")
     * 
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function updatePassword(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder){
        $updatePassword = new UpdatePassword();
        $user = $this->getUser();
        $form = $this->createForm(UpdatePasswordType::class, $updatePassword);
        $form->handleRequest($request);

        //verify if the feilds are valid according to Asserts andif form is submitted.
        if($form->isSubmitted() && $form->isValid()){

            //verify if the old PW feild is equal to the hashed PW stored in the database.
            if(!password_verify($updatePassword->getOldPassword(), $user->getHash())){
                //error
                $this->addFlash('danger', "Une erreur s'est produit, veuillez réessayer !");
                return $this->redirectToRoute('account_password');
            } else {
                $hash = $encoder->encodePassword($user, $updatePassword->getNewPassword());
                $user->setHash($hash);
                $manager->persist($user);
                $manager->flush();
                $this->addFlash('success', "Votre mot de passe a bien été modifié !");
            }
            return $this->redirectToRoute('homepage');
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }




    /**
     * leads to app.user s page
     *
     * @Route("/account", name="account_index")
     * 
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function myAccount(){
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser()
        ]);


    }



    /**
     * list of all bookings for a user
     * 
     * @Route("/account/bookings", name="account_bookings")
     *
     * @return Response
     */
    public function bookings(){
        return $this->render('account/bookings.html.twig');
    }
}
