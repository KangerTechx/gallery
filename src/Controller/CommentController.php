<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\PictureRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{


    #[Route('/comment', name: 'comment')]
    public function index(CommentRepository $commentRepository, PictureRepository $pictureRepository, UserRepository $userRepository): Response
    {
        $comment = $commentRepository->findAll();
        $picture = $pictureRepository->findAll();
        $user = $userRepository->findAll();

        return $this->render('comment/index.html.twig', [
            'comments' => $comment,
            'picture' => $picture,
            'user' =>$user
        ]);
    }
    #[Route('/delComment/{id}', name: 'delComment')]
    public function delComment(Comment $comment, EntityManagerInterface $manager) : Response
    {
        $manager->remove($comment);
        $manager->flush();
        return $this->redirectToRoute('comment');
    }
}
