<?php

namespace App\Controller;
use App\Entity\Artist;
use App\Entity\Picture;
use App\Repository\ArtistRepository;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PictureRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @param ArtistRepository $artistRepository
     * @return Response
     */
    #[Route('/', name: 'home')]
    public function index(ArtistRepository $artistRepository, PictureRepository $pictureRepository): Response
    {
        $artist = $artistRepository->findBy(
            ['isDisabled' => false],
            ['id' => 'DESC'],
             3
        );

        $picture = $pictureRepository->findBy(
            ['isPulished' => true],
            ['id' => 'DESC'],
             3
        );

        return $this->render('home/index.html.twig', [
            'artistes' => $artist,
            'pictures' => $picture
        ]);
    }

    /**
     * @param UserRepository $userRepository
     * @param ArtistRepository $artistRepository
     * @return Response
     */
    #[Route('/about', name: 'about')]
    public function about(UserRepository $userRepository, ArtistRepository $artistRepository) :Response
    {
        $users = $userRepository->findAll();

        $adminUsers = [];

        foreach ($users as $user) {
            if (in_array('ROLE_ADMIN', $user->getRoles())){
                $adminUsers[] = $user;
            }
        }

        $artist = $artistRepository->findBy(
            ['isDisabled' =>false],
            ['lastname' => 'ASC']
        );

        return $this->render('home/about.html.twig', [
            'users' => $adminUsers,
            'artists' => $artist
        ]);
    }

    /**
     * @param PictureRepository $pictureRepository
     * @param CategoryRepository $categoryRepository
     * @param ArtistRepository $artistRepository
     * @return Response
     */
    #[Route('/gallery', name: 'gallery')]
    public function gallery(PictureRepository $pictureRepository, CategoryRepository $categoryRepository, ArtistRepository $artistRepository) :Response
    {
        $picture = $pictureRepository->findBy(
            ['isPulished' => true],
            ['title' => 'ASC']
        );

        $category = $categoryRepository->findAll();

        $artist = $artistRepository->findBy(
            ['isDisabled' => false]
        );

        return $this->render('home/gallery.html.twig', [
            'pictures' => $picture,
            'artists' => $artist,
            'category' => $category
        ]);
    }


    /**
     * @param Artist $artist
     * @return Response
     */
    #[Route('/artDetail/{id}', name: 'artDetail')]
    public function artistDetails(Artist $artist) : Response
    {
        return $this->render('home/artDetail.html.twig', [
            'artist' => $artist
        ]);
    }


    /**
     * @param Picture $picture
     * @return Response
     */
    #[Route('/picDetail/{id}', name: 'picDetail')]
    public function pictureDetails(Picture $picture, CommentRepository $commentRepository) : Response
    {

        $comments = $commentRepository->findBy(
            ['isPublished' => true],
            ['id' => 'DESC']
        );

        return $this->render('home/picDetail.html.twig', [
            'picture' => $picture,
            'comments' =>$comments
            ]);
    }


}
