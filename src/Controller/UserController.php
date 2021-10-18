<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditAdminType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @param UserRepository $userRepository
     * @return Response
     */
    #[Route('/user', name: 'user')]
    public function index(UserRepository $userRepository): Response
    {
        $user = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $user
        ]);
    }


    /**
     * @param User $user
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    #[Route('/editUserAdmin/{id}', name: 'editUserAdmin')]
    public function editUser(User $user, EntityManagerInterface $manager, Request $request) : Response
    {
    $form = $this->createForm(UserEditAdminType::class, $user);
    $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            if ($user->getIsDisabled() == true) {
                $comments = $user->getComments();

                foreach ($comments as $comment ) {
                    $comment->setIsPublished(false);
                    $manager->persist($comment);
                }
            }
            $manager->flush();
            return $this->redirectToRoute('user');
        }
        return $this->render('user/editUser.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
