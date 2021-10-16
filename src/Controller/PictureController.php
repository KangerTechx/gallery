<?php

namespace App\Controller;

use App\Repository\ArtistRepository;
use App\Repository\CategoryRepository;
use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PictureController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(PictureRepository $pictureRepository, CategoryRepository $categoryRepository, ArtistRepository $artistRepository): Response
    {
        $category = $categoryRepository->findAll();

        $pictures = $pictureRepository->findAll();

        $artist = $artistRepository->findAll();

        return $this->render('picture/index.html.twig', [
            'pictures' => $pictures,
            'categories' => $category,
            'artists' => $artist

        ]);
    }


}
