<?php

declare(strict_types=1);

namespace App\Controller\user;

use DateTime;
use App\Entity\User;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{

    #[Route("/utilisateur/mon-compte", name: "app_user_show_account", methods: ["GET"])]
    public function showAccount(UserRepository $repository): Response
    {
        $user = $repository->find($this->getUser());

        $bookings = $user->getBookings();
        $comments = $user->getComments();

        return $this->render('user/account/show.html.twig', [
            'bookings' => $bookings,
            'comments' => $comments,
        ]);
    }

    
    #[Route("/utilisateur/reservation/creer", name: "app_user_booking_add", methods: ["GET", "POST"])]
    public function addBooking(Request $request, EntityManagerInterface $entitymanager, UserRepository $userRepository): Response
    {

        $booking = new Booking();
        $user = $userRepository->find($this->getUser());

        $form = $this->createForm(BookingType::class, $booking)->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $booking->setCreatedAt(new DateTime());
            $booking->setUpdatedAt(new DateTime());
            $booking->setUser($user);


            $entitymanager->persist($booking);
            $entitymanager->flush();

            return $this->redirectToRoute('app_user_show_account');
        }

        return $this->render('user/booking/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route("/utilisateur/commentaire/creer", name: "app_user_comment_add", methods: ["GET", "POST"])]
    public function addComment(Request $request, EntityManagerInterface $entitymanager, UserRepository $userRepository): Response
    {

        $comment = new Comment();
        $user = $userRepository->find($this->getUser());

        $form = $this->createForm(CommentType::class, $comment)->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $comment->setCreatedAt(new DateTime());
            $comment->setUser($user);


            $entitymanager->persist($comment);
            $entitymanager->flush();

            return $this->redirectToRoute('app_user_show_account');
        }

        return $this->render('user/comment/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/utilisateur/commentaire/{id}/supprimer', name: 'app_user_comment_delete')]
	public function deleteComment(Comment $comment, CommentRepository $repository): Response
	{
		$repository->remove($comment);

		return $this->redirectToRoute('app_user_show_account');
	}
}
