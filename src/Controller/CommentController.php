<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\PictureRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @param Comment $comment
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/delComment/{id}', name: 'delComment')]
    public function delComment(Comment $comment, EntityManagerInterface $manager) : Response
    {
        $manager->remove($comment);
        $manager->flush();
        return $this->redirectToRoute('comment');
    }


    #[Route('/addComment/{id}', name: 'addComment')]
    public function addComment(Request $request, EntityManagerInterface $manager, int $id, PictureRepository $pictureRepository) : Response
    {
        $paint = $pictureRepository->find($id);
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $pseu = $request->request->get('_pseudo');
            $pseudo =  (int)$pseu;
            dd($pseudo);
            $comment->setCreatedAt(new \DateTimeImmutable())
                    ->setIsPublished(true)
                    ->setPicture($paint)
                    ->setPseudo($pseudo);
            $manager->persist($comment);
            $manager->flush();
            return $this->redirectToRoute('picDetail');
        }
        return $this->render('home/addComment.html.twig', [
            'form' => $form->createView(),
            'picture' => $paint
        ]);
    }
}
