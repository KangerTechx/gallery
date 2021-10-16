<?php

namespace App\Controller;

use App\Repository\ArtistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtistController extends AbstractController
{
    #[Route('/artist', name: 'artist')]
    public function index(ArtistRepository $artistRepository): Response
    {
        $artist = $artistRepository->findBy([
            'lastname' => 'ASC'
        ]);
        return $this->render('artist/index.html.twig', [
            'artist' => $artist
        ]);
    }
}
