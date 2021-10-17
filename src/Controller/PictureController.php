<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Form\PictureEditType;
use App\Form\PictureType;
use App\Repository\ArtistRepository;
use App\Repository\CategoryRepository;
use App\Repository\PictureRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PictureController extends AbstractController
{
    /**
     * @param PictureRepository $pictureRepository
     * @param CategoryRepository $categoryRepository
     * @param ArtistRepository $artistRepository
     * @return Response
     */
    #[Route('/admin', name: 'admin')]
    public function index(PictureRepository $pictureRepository, CategoryRepository $categoryRepository, ArtistRepository $artistRepository): Response
    {
        $category = $categoryRepository->findAll();

        $pictures = $pictureRepository->findAll();

        $artist = $artistRepository->findBy([
            'isDisabled' => false
        ]);

        return $this->render('picture/index.html.twig', [
            'pictures' => $pictures,
            'categories' => $category,
            'artists' => $artist

        ]);
    }


    /**
     * @param PictureRepository $pictureRepository
     * @param CategoryRepository $categoryRepository
     * @param ArtistRepository $artistRepository
     * @param $id
     * @return Response
     */
    #[Route('/cat/{id}', name: 'cat')]
    public function catFilter(PictureRepository $pictureRepository, CategoryRepository $categoryRepository, ArtistRepository $artistRepository, $id) : Response
    {
        $category = $categoryRepository->findAll();

        $pictures = $pictureRepository->findBy([
            'category' => $id
            ]);

        $artist = $artistRepository->findAll();

        return $this->render('picture/index.html.twig', [
            'pictures' => $pictures,
            'categories' => $category,
            'artists' => $artist

        ]);
    }

    /**
     * @param PictureRepository $pictureRepository
     * @param CategoryRepository $categoryRepository
     * @param ArtistRepository $artistRepository
     * @param $id
     * @return Response
     */
    #[Route('/art/{id}', name: 'art')]
    public function artFilter(PictureRepository $pictureRepository, CategoryRepository $categoryRepository, ArtistRepository $artistRepository, $id) : Response
    {
        $category = $categoryRepository->findAll();

        $pictures = $pictureRepository->findBy([
            'artist' => $id
        ]);

        $artist = $artistRepository->findAll();

        return $this->render('picture/index.html.twig', [
            'pictures' => $pictures,
            'categories' => $category,
            'artists' => $artist

        ]);
    }


    /**
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/addPicture', name: 'addPicture')]
    public function addPicture(Request $request, EntityManagerInterface $manager): Response
    {
        $slugify = New Slugify();
        $picture = new Picture;
        $form = $this->createForm(PictureType::class, $picture);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();


            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugify->slugify($originalFilename);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$image->guessExtension();

                try {
                    $image->move(
                    $this->getParameter('picture_directory'),
                    $newFileName
                    );
                } catch (FileException $e) {
                    // rajouter message d'erreur
                }
                $picture->setImage($newFileName);
            }
            $picture->setSlug($slugify->slugify($picture->getTitle()));
            $manager->persist($picture);
            $manager->flush();
            return $this->redirectToRoute('admin');
        }

        return $this->renderForm('picture/addPicture.html.twig', [
            'form' => $form
    ]);
    }


    /**
     * @param Picture $picture
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/delPicture/{id}', name: 'delPicture')]
    public function delPicture(Picture $picture, EntityManagerInterface $manager) : Response
    {
        $image = $picture->getImage();
        $fileSystem = new FileSystem();
        $projectDir = $this->getParameter('picture_directory');
        $fileSystem->remove($projectDir.'/'.$image);
        $manager->remove($picture);
        $manager->flush();
        return $this->redirectToRoute('admin');
    }


    /**
     * @param Picture $picture
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    #[Route('/editPicture/{id}', name: 'editPicture')]
    public function editPicture(Picture $picture, EntityManagerInterface $manager, Request $request) :Response
    {

        $form = $this->createForm(PictureEditType::class, $picture);
        $form->handleRequest($request);


        if($form->isSubmitted()&& $form->isValid()) {
           $manager->persist($picture);
           $manager->flush();
           return $this->redirectToRoute('admin');
        }
        return $this->render('picture/editPicture.html.twig', [
           'form' => $form->createView()
        ]);
    }

}
