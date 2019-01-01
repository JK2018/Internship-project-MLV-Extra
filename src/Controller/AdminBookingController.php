<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/bookings", name="admin_booking_index")
     */
    public function index(BookingRepository $repo)
    {
        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $repo->findAll(),
        ]);
    }




    /**
     * to edit an existing booking
     * 
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/booking/{id}/edit", name="admin_booking_edit")
     *
     * @return Response
     */
    public function edit(ObjectManager $manager, Booking $booking, Request $request ){

        $form = $this->createForm(AdminBookingType::class, $booking);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $booking->setAmount($booking->getAd()->getHoursPerDay() * $booking->getDuration());
            $manager->persist($booking);
            $manager->flush();
        
        $this->addFlash(
            'success',
            "La réservation n° {$booking->getId()} a bien été modifiée !"
        );
        return $this->redirectToRoute("admin_booking_index");
    }

        return $this->render('admin/booking/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }


    /**
     * delete a booking
     * @Route("/admin/booking/{id}/delete", name="admin_booking_delete")
     * @IsGranted("ROLE_ADMIN")
     *
     * @return Response
     */
    public function delete(Booking $booking, ObjectManager $manager){

        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            "La réservation a bien été modifiée !"
        );

        return $this->redirectToRoute("admin_booking_index");
    }
}
