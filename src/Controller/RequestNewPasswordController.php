<?php

namespace App\Controller;

use App\Entity\LostPassword;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class RequestNewPasswordController extends AbstractController
{
    public function __construct(EventDispatcherInterface $dispatcher, ManagerRegistry $entityManager)
    {
        $this->eventDispatcher = $dispatcher;
        $this->entityManager = $entityManager;
    }

    #[Route('/recover-password', name: 'recover')]
    public function index(Request $request): Response
    {
        $email = $request->get('_email');

        $repository = $this->entityManager->getManager()->getRepository(User::class);
        $user = $repository->findOneBy(['email' => $email]);

        if ($user) {
            $lostPassword = new LostPassword();
            $lostPassword->setEmail($email);
            $user->setLostPassword($lostPassword);
            $this->entityManager->getManager()->flush();

            $this->sendEmail($user);

           return $this->render('request_new_password/after-restore.html.twig');
        }

        return $this->render('request_new_password/index.html.twig');
    }

    public function sendEmail($user)
    {
        $transport = Transport::fromDsn($this->getParameter('app.mailer_dsn'));
        $mailer = new Mailer($transport);

        $user_id = $user->getId();
        $message = (new Email())
            ->from($this->getParameter('app.mailer_from'))
            ->to($user->getEmail())
            ->subject('New password request')
            ->text(
                'You requested a new password. Use this link to reset your password: '. $this->getParameter('app_url') .'/reset/'.$user_id.'/change-password?token='.$user->getLostPassword()->getToken()
            );

        $mailer->send($message);
    }
}
