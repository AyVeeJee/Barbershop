<?php

namespace App\Controller;

use App\Entity\LostPassword;
use App\Entity\User;
use App\Form\NewPasswordType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/api/reset/{id}/change-password', name: 'reset_password')]
    public function index(Request $request, User $user, UserPasswordHasherInterface $encoder): Response
    {
        if($request->get('token') === $user->getLostPassword()->getToken())
        {
            $entityManager = $this->doctrine->getManager();

            $form = $this->createForm(NewPasswordType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                $user->setPassword($encoder->hashPassword($user, $form->get('password')->getData()));

                $repository = $this->doctrine->getRepository(LostPassword::class);
                $item = $repository->find($user->getLostPassword()->getId());
                $entityManager->remove($item);

                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('login');
            }

            return $this->render('reset_password/index.html.twig', [
                'error' => '',
                'form' => $form->createView(),
            ]);
        }

        return $this->render('reset_password/index.html.twig', [
            'error' => 'Invalid data',
        ]);
    }
}
