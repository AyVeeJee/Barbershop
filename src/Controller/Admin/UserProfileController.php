<?php

namespace App\Controller\Admin;

use App\Form\ProfileType;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
{
    private ManagerRegistry $entityManager;
    private UserPasswordHasherInterface $passwordEncoder;

    public function __construct(ManagerRegistry $entityManager, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/admin/profile", name="admin_profile")
     */
    public function index(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $this->passwordEncoder->hashPassword($user, $form->get('password')->getData())
            );
            $this->entityManager->getManager()->persist($user);
            $this->entityManager->getManager()->flush();
            return $this->redirectToRoute('admin', $request->query->all());
        }
        return $this->render('admin/profile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
