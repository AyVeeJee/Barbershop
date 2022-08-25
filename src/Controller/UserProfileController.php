<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use App\Form\NewPasswordType;
use App\Form\UserPersonalDetailsType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_USER')]
class UserProfileController extends AbstractController
{
    private ManagerRegistry $entityManager;
    private UserPasswordHasherInterface $passwordEncoder;

    public function __construct(ManagerRegistry $entityManager, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    #[Route('/user/profile', name: 'user_profile')]
    public function index(Request $request): Response
    {
        $comments = null;
        $userRepository = $this->entityManager->getManager()->getRepository(User::class);
        $user = $userRepository->findOneBy(['id' => $this->getUser()->getId()]);

        if (!$user->getComments()->isEmpty()) {
            $comments = $user->getComments();
        }

        $personalDetailsForm = $this->createForm(UserPersonalDetailsType::class, $user);
        $passwordForm = $this->createForm(NewPasswordType::class, $user);

        $personalDetailsForm->handleRequest($request);
        $passwordForm->handleRequest($request);

        $entityManager = $this->entityManager->getManager();

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $user->setPassword($this->passwordEncoder->hashPassword($user, $passwordForm->get('password')->getData()));

            $entityManager->persist($user);
            $entityManager->flush();
        }

        if ($personalDetailsForm->isSubmitted() && $personalDetailsForm->isValid()) {
            $user->setFirstName($personalDetailsForm->get('first_name')->getData());
            $user->setLastName($personalDetailsForm->get('last_name')->getData());
            $user->setPhone($personalDetailsForm->get('phone')->getData());
            $user->setEmail($personalDetailsForm->get('email')->getData());

            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('user_profile/index.html.twig', [
            'personalDetailsForm' => $personalDetailsForm->createView(),
            'passwordForm' => $passwordForm->createView(),
            'user' => $user,
            'comments' => $comments,
        ]);
    }

    #[Route('/user/profile/update', name: 'user_profile_comment_update')]
    public function updateComment(Request $request): RedirectResponse
    {
        $commentId = $request->get('employee_id');
        $userId = $this->getUser()->getId();
        $content = $request->get('content');

        $entityManager = $this->entityManager->getManager();
        $comment = $entityManager->getRepository(Comment::class)->findOneBy(['user_comment' => $userId, 'id' => $commentId]);

        if (!$comment) {
            throw $this->createNotFoundException(
                'No comment found for id '.$commentId
            );
        }

        $comment->setContent($content);
        $entityManager->flush();

        return $this->redirectToRoute('user_profile');
    }

    #[Route('/user/profile/delete', name: 'user_profile_comment_delete', methods: ['POST'])]
    public function deleteComment(Request $request): RedirectResponse
    {
        $commentId = $request->get('comment_id');
        $userId = $this->getUser()->getId();

        $entityManager = $this->entityManager->getManager();
        $comment = $entityManager->getRepository(Comment::class)->findOneBy(['user_comment' => $userId, 'id' => $commentId]);

        if (!$comment) {
            throw $this->createNotFoundException(
                'No comment found for id '.$commentId
            );
        }

        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirectToRoute('user_profile');
    }
}
