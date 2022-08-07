<?php

declare(strict_types=1);

namespace App\Controller\admin;

use DateTime;
use App\Entity\Food;
use App\Form\FoodType;
use App\Repository\FoodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FoodController extends AbstractController
{
    #[Route("/admin/dashboard", name: "app_admin_show_dashboard", methods: ["GET"])]
    public function showDashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    #[Route("/admin/afficher-menu", name: "app_admin_food_list", methods: ["GET"])]
    public function listFood(FoodRepository $repository): Response
    {
        $foods = $repository->findAll();

        return $this->render('admin/food/list.html.twig', [
            'foods' => $foods,
        ]);
    }

    #[Route("/admin/produit/ajouter", name: "app_admin_food_add", methods: ["GET", "POST"])]
    public function addFood(Request $request, EntityManagerInterface $entitymanager, SluggerInterface $slugger): Response
    {

        $food = new Food();

        $form = $this->createForm(FoodType::class, $food)->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $food->setCreatedAt(new DateTime());
            $food->setUpdatedAt(new DateTime());

            /** @var UploadedFile $picture */
            $picture = $form->get('picture')->getData();

            if($picture) {
                
                $this->handleFile($food, $picture, $slugger);
            }

            $entitymanager->persist($food);
            $entitymanager->flush();

            return $this->redirectToRoute('app_admin_food_list');
        }

        return $this->render('admin/food/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('admin/produit/{id}/modifier', name: 'app_admin_food_update', methods:['GET', 'POST'])]
    public function updateFood(Food $food, EntityManagerInterface $entitymanager, Request $request, SluggerInterface $slugger)
    {
        $originalPicture = $food->getPicture();

        $form = $this->createForm(FoodType::class, $food, [
            'picture' => $originalPicture
        ])->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $food->setUpdatedAt(new DateTime());

            $picture = $form->get('picture')->getData();

            if($picture) {
                
                $this->handleFile($food, $picture, $slugger);
            }
            else {
                $food->setPicture($originalPicture);            }

            $entitymanager->persist($food);
            $entitymanager->flush();

            return $this->redirectToRoute('app_admin_food_list');
        }

        return $this->render('admin/food/update.html.twig', [
            'form' => $form->createView(),
            'food' => $food,
        ]);
    }

    #[Route('/admin/produit/{id}/supprimer', name: 'app_admin_food_delete')]
	public function deleteFood(Food $food, FoodRepository $repository): Response
	{
		$repository->remove($food);

		return $this->redirectToRoute('app_admin_food_list');
	}




        //  Gestion de l'ajout de photo :

    private function handleFile(Food $food, UploadedFile $picture, SluggerInterface $slugger): void
    {

        $extension = '.' . $picture->guessExtension();

        $safeFilename = $slugger->slug($food->getTitle());

        $newFilename = $safeFilename . '_' . uniqid() . $extension;

        try {
            $picture->move($this->getParameter('uploads_dir'), $newFilename);
            $food->setPicture($newFilename);
        } catch (FileException $exception) {
            $this->addFlash('warning', 'La photo du produit ne s\'est pas importée avec succès. Veuillez réessayer en modifiant le produit.');
        } // end catch()
    }

}


