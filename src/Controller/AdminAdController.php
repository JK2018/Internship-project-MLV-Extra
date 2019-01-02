<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * lists ads with pagination. route has optional(?) parameter page that uses numeric only constraint(<>) and default value as 1.
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     */
    public function index(AdRepository $repo, $page)
    {
        $limit = 10;
        $start = $page * 10 - 10; //determins the offset for pagination.
        $total = count($repo->findAll());
        $pages = ceil($total / $limit);// rounds number above.

        return $this->render('admin/ad/index.html.twig', [
            'ads' => $repo->findBy([], [], $limit, $start),
            'pages' => $pages,
            'page' => $page
        ]);
    }


/*
    public function edit(Ad $ad){
        $form = $this->createForm(AdType::class, $ad);

        return $this->render('admin/ad/edit.html.twig',[

        ])
    }*/
}
