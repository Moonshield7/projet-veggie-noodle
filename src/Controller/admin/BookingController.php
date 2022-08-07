<?php

declare(strict_types=1);

namespace App\Controller\admin;

use App\Entity\Booking;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    #[Route("/admin/afficher-reservations", name: "app_admin_booking_list", methods: ["GET"])]
    public function listBookings(BookingRepository $repository): Response
    {
        $bookings = $repository->findAll();

        return $this->render('admin/bookings/list.html.twig', [
            'bookings' => $bookings,
        ]);
    }

    #[Route('/admin/reservation/{id}/supprimer', name: 'app_admin_booking_delete')]
	public function deleteBooking(Booking $booking, BookingRepository $repository): Response
	{
		$repository->remove($booking);

		return $this->redirectToRoute('app_admin_booking_list');
	}


}
