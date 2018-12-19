<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Entity\Image;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\Query\Expr\OrderBy;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     * 
     * @IsGranted("ROLE_USER")
     */
    public function index(AdRepository $repo)
    {
        //$repo = $this->getDoctrine()->getRepository(Ad::class);
        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
           // 'controller_name' => 'AdController',
           'ads' => $ads
        ]);
    }



    /**
     * create a new ad and redirects to the created ad if valid.
     * placed above show function so that paramConverter does now interfere thinking new is a slug.
     *
     * @Route("/ads/new", name="ads_create")
     * 
     * Only admins may create ads!
     * @IsGranted("ROLE_ADMIN")
     * 
     * @return Response
     */
    public function create(Request $request, ObjectManager $manager){

        $ad = new Ad;
 
        $form = $this->createForm(AdType::class, $ad); // created form class AdType via cmd to keep controller slim.
        $form->handleRequest($request); //puts request info into $ad.

        if($form->isSubmitted() && $form->isValid()){
           
            // for each image we add it to the ad and persist the image to prevent (non persisted entities)error when submitting form
            foreach($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);
            }

            $ad->setAuthor($this->getUser());

            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success', //label
                "L'offre <strong>{$ad->getTitle()}</strong> a été enregistrée avec succés!" //message
            );

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);

    }




    /**
     * to access the form that allows to edit an existing ad.
     *
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * 
     * Only admins may edit ads!
     * @IsGranted("ROLE_ADMIN")
     * 
     * @return Response
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager){
        $form = $this->createForm(AdType::class, $ad); // created form class AdType via cmd to keep controller slim.
        $form->handleRequest($request); //puts request info into $ad.

        if($form->isSubmitted() && $form->isValid()){
            // for each image we add it to the ad and persist the image to prevent (non persisted entities)error when submitting form
            foreach($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success', //label
                "Les modifications de l'offre <strong>{$ad->getTitle()}</strong> ont été enregistrées avec succés!" //message
            );
            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }
        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }




    /**
     * shows one ad.
     *
     * @Route("/ads/{slug}", name="ads_show")
     * 
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function show(/*$slug, AdRepository $repo*/ Ad $ad) //usage of paramConverter for Ad 
    {
        
        /*$ad = $repo->findOneBySlug($slug);*/ // recovers the ad that corresponds to $slug.

        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }



    /**
     * Undocumented function
     * @Route("/ads/{slug}/delete", name="ads_delete")
     * @IsGranted("ROLE_ADMIN")
     * @return Response
     */
    public function delete(ObjectManager $manager, Ad $ad){
        $manager->remove($ad);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'Xtra <strong>{$ad->getTitle()}</strong> a bien été supprimé !"
        );

        return $this->redirectToRoute("ads_index");
    }


}
