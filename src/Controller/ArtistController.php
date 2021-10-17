<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistEditType;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtistController extends AbstractController
{
    /**
     * @param ArtistRepository $artistRepository
     * @return Response
     */
    #[Route('/artist', name: 'artist')]
    public function index(ArtistRepository $artistRepository): Response
    {
        $artist = $artistRepository->findAll();
        return $this->render('artist/index.html.twig', [
            'artists' => $artist
        ]);
    }


    /**
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/addArtist', name: 'addArtist')]
    public function addArtist(Request $request, EntityManagerInterface $manager): Response
    {
        $slugify = New Slugify();
        $artist = new Artist;
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();

            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugify->slugify($originalFilename);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('artist_directory'),
                        $newFileName
                    );
                } catch (FileException $e) {
                    // rajouter message d'erreur
                }
                $artist->setImage($newFileName);
            }
            $manager->persist($artist);
            $manager->flush();
            return $this->redirectToRoute('artist');
        }

        return $this->renderForm('artist/addArtist.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @param Artist $artist
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    #[Route('/editArtist/{id}', name: 'editArtist')]
    public function editArtist(Artist $artist, EntityManagerInterface $manager, Request $request) : Response
    {
        $form = $this->createForm(ArtistEditType::class, $artist);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()) {
            $manager->persist($artist);
            $manager->flush();
            return $this->redirectToRoute('artist');
        }
        return $this->render('artist/editArtist.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
