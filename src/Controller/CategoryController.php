<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'category' => $category
        ]);
    }


    /**
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/addCategory', name: 'addCategory')]
    public function addCategory(Request $request, EntityManagerInterface $manager) :Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($category);
            $manager->flush();
            return $this->redirectToRoute('category');
        }
        return $this->renderForm('category/addCategory.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @param Category $category
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    #[Route('/editCategory/{id}', name: 'editCategory')]
    public function editCategory(Category $category, EntityManagerInterface $manager, Request $request): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);


        if($form->isSubmitted()&& $form->isValid()) {
            $manager->persist($category);
            $manager->flush();
            return $this->redirectToRoute('category');
        }
        return $this->render('category/editCategory.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
