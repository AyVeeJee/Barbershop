<?php

namespace App\Controller;

use App\Entity\LostPassword;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class RequestNewPasswordController extends AbstractController
{
    const requestError = 'Please, check your email';
    private EventDispatcherInterface $eventDispatcher;
    private ManagerRegistry $entityManager;

    public function __construct(EventDispatcherInterface $dispatcher, ManagerRegistry $entityManager)
    {
        $this->eventDispatcher = $dispatcher;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/recover-password', name: 'recover')]
    public function index(Request $request): Response
    {
        $error = '';
        $email = $request->get('_email');

        $repository = $this->entityManager->getManager()->getRepository(User::class);
        $lostPassword = $this->entityManager->getManager()
            ->getRepository(LostPassword::class)
            ->findOneBy(
                [
                    'email' => $email,
                    'active' => true,
                ]);

        $user = $repository->findOneBy(['email' => $email]);

        if ($user) {
            if (!$lostPassword) {
                $lostPassword = new LostPassword();
                $lostPassword->setEmail($email);
                $lostPassword->setActive(true);
                $lostPassword->setOldPassword($user->getPassword());
            }

            $user->setLostPassword($lostPassword);
            $this->entityManager->getManager()->flush();

            $this->sendEmail($user);

           return $this->render('request_new_password/after-restore.html.twig');
        }

        if ($email) {
            $error = self::requestError;
        }

        return $this->render('request_new_password/index.html.twig', ['error' => $error]);
    }

    /**
     * @throws TransportExceptionInterface
     */
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
