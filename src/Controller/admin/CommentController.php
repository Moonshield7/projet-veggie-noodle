<?php

declare(strict_types=1);

namespace App\Controller\admin;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    #[Route("/admin/afficher-commentaires", name: "app_admin_comment_list", methods: ["GET"])]
    public function listComments(CommentRepository $repository): Response
    {
        $comments = $repository->findAll();
        

        return $this->render('admin/comments/list.html.twig', [
            'comments' => $comments,
        ]);
    }

    #[Route('/admin/commentaires/{id}/supprimer', name: 'app_admin_comment_delete')]
	public function deleteComment(Comment $comments, CommentRepository $repository): Response
	{
		$repository->remove($comments);

		return $this->redirectToRoute('app_admin_comment_list');
	}
}
