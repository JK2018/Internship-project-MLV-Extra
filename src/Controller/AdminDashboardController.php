<?php

namespace App\Controller;

use App\Service\StatsService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(ObjectManager $manager, StatsService $statsService)
    {


        $users = $statsService->getUsersCount();
        $ads = $statsService->getAdsCount();
        $comments = $statsService->getCommentsCount();
        $bookings = $statsService->getBookingsCount();

        $bestAds = $statsService->getBestAds();
        $worstAds = $statsService->getWorstAds();

        $bestWorker = $statsService->getMostTimeWorked();
        $notBestWorker = $statsService->getLessTimeWorked();


        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => [
                'users' => $users,
                'ads' => $ads, 
                'bookings' => $bookings, 
                'comments' => $comments
            ],
            'bestAds' => $bestAds,
            'worstAds' => $worstAds,
            'bestWorker' => $bestWorker,
            'notBestWorker' => $notBestWorker

        ]);
    }
}
