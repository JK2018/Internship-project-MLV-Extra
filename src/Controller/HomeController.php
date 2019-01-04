<?php
namespace App\Controller;

use App\Service\StatsService;
use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class HomeController extends Controller{
    
    /**
     * @Route("/", name="homepage")
     * 
     * @IsGranted("ROLE_USER")
     * 
     * 
     * 
     * @return void
     */
    public function home(AdRepository $adRepo, UserRepository $userRepo, StatsService $statsService){

        $bestWorker = $statsService->getMostTimeWorked(2);
        
        return $this->render(
            'home.html.twig',
            [
                'ads' => $adRepo->findBestAds(3),
                'bestWorker' => $bestWorker,
            ]
        );
    }
}






?>