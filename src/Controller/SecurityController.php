<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */
    #[Route('/registration', name: 'registration')]
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        $slug = new Slugify();
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slug->slugify($originalFilename);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('user_directory'),
                        $newFileName
                    );
                } catch (FileException $e) {
                    // rajouter message d'erreur flash
                }
                $user->setImage($newFileName);
            } else {
                $user->setImage('default.png');
            }

            $hash = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hash);
            $pseudo = $slug->slugify($user->getPseudo());
            $user->setPseudo($pseudo);
            $user->setIsDisabled(false);
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('login');
        }
        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @return Response
     */
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils) : Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUserName = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUserName,
            'error' =>$error
        ]);
    }


    /**
     * @return Response
     */
    #[Route('/logout', name: 'logout')]
    public function logout() : Response
    {
    }
}
