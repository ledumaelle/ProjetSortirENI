<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ResetPaswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 * @package App\Controller
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        //empêcher l'user de s'afficher au formulaire de login
        if ($this->getUser()) {
            return $this->redirectToRoute('app_homepage');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/send_reset_psw", name="send_reset_psw")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function SendResetPsw(Request $request)
    {
        $participantRepository = $this->getDoctrine()->getRepository(Participant::class);

        $participant = $participantRepository->findOneBy(['mail' => $request->get('email')]);
        if ($participant === null) {
            $this->addFlash('danger', 'Cet utilisateur est inconnu êtes vous sûre de possèder un compte ?');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/email_reset_password.html.twig', [
            'mailTo' => $request->get('email'),
            'mail' => base64_encode($request->get('email'))
        ]);
    }

    /**
     * @param string $email
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $encoder
     * @return RedirectResponse|Response
     * @Route(path="/reset_password/{email}", name="reset_password")
     */
    public function resetPassword(string $email, Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder)
    {
        $emailDecode = base64_decode($email);
        $participantRepository = $this->getDoctrine()->getRepository(Participant::class);


        /** @var Participant $participant */
        $participant = $participantRepository->findOneBy(['mail' => $emailDecode]);

        if (null !== $participant) {
            $resetPwdForm = $this->createForm(ResetPaswordType::class, $participant);

            $resetPwdForm->handleRequest($request);
            if ($resetPwdForm->isSubmitted() && $resetPwdForm->isValid()) {
                $password = $encoder->encodePassword($participant, $participant->getMotPasse());
                $participant->setMotPasse($password);

                $entityManager->persist($participant);
                $entityManager->flush();

                return $this->redirectToRoute('app_login');
            }

            return $this->render('security/reset_password.html.twig', [
                'mail' => $email,
                'resetPwdForm' => $resetPwdForm->createView()
            ]);
        }

        return $this->redirectToRoute('app_login');
    }
}
