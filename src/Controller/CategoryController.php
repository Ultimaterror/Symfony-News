<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/categories')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'category_list')]
    public function list(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('category/list.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/{id}', name: "category_item")]
    public function item(Category $category): Response
    {
        return $this->render('category/item.html.twig', [
            'category' => $category
        ]);
    }

    #[Route('/new', name: "category_new", methods: ["GET", "POST"])]
    public function newCategory(Request $request, EntityManagerInterface $em): void
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Si tout va bien, alors on peut persister l'entité et valider les modifications en BDD
            $em->persist($category);
            $em->flush();
        }
    }
}
