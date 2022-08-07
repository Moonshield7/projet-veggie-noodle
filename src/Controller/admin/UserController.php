<?php

declare(strict_types=1);

namespace App\Controller\admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route("/admin/afficher-utilisateurs", name: "app_admin_user_list", methods: ["GET"])]
    public function listUser(UserRepository $repository): Response
    {
        $users = $repository->findAll();

        return $this->render('admin/users/list.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/utilisateur/{id}/supprimer', name: 'app_admin_user_delete')]
	public function deleteUser(User $user, UserRepository $repository): Response
	{
		$repository->remove($user);

		return $this->redirectToRoute('app_admin_user_list');
	}
}
