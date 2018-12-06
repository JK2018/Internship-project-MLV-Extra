<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
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
     * shows one ad.
     *
     * @Route("/ads/{slug}", name="ads_show")
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
 * create a new ad.
 *
 * @Route("/ads/new", name="ads_create")
 * @return Response
 */
    public function create(){
        return $this->render('ad/new.html.twig');
    }
}
