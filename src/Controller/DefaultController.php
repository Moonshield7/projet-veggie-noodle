<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\FoodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route("/", name: "app_default_show_home", methods: ["GET"])]
    public function showHome(CommentRepository $repository): Response
    {
        $comments = $repository->findAll();

        return $this->render('default/home.html.twig', [
            'comments' => $comments,
        ]);
    }

    #[Route("/entrees", name: "app_default_show_starter", methods: ["GET"])]
    public function showStarter(FoodRepository $repository): Response
    {

        $starters = $repository->findBy(array('type' => 'Entrée'));

        return $this->render('default/starter.html.twig', [
            'starters' => $starters
        ]);
    }

    #[Route("/plats", name: "app_default_show_dish", methods: ["GET"])]
    public function showDish(FoodRepository $repository): Response
    {

        $dishes = $repository->findBy(array('type' => 'Plat'));

        return $this->render('default/dish.html.twig', [
            'dishes' => $dishes
        ]);
    }

    #[Route("/desserts", name: "app_default_show_dessert", methods: ["GET"])]
    public function showDessert(FoodRepository $repository): Response
    {

        $desserts = $repository->findBy(array('type' => 'Dessert'));

        return $this->render('default/desserts.html.twig', [
            'desserts' => $desserts
        ]);
    }

    #[Route("/boissons", name: "app_default_show_drink", methods: ["GET"])]
    public function showDrink(FoodRepository $repository): Response
    {

        $drinks = $repository->findBy(array('type' => 'Boisson'));

        return $this->render('default/drink.html.twig', [
            'drinks' => $drinks
        ]);
    }

    #[Route("/politique-de-confidentialite", name: "app_default_show_privacy", methods: ["GET"])]
    public function showPrivacy(): Response
    {
        return $this->render('default/privacy.html.twig');
    }

    #[Route("/mentions-legales", name: "app_default_show_legalnotice", methods: ["GET"])]
    public function showLegalNotice(): Response
    {
        return $this->render('default/legalNotice.html.twig');
    }
}
