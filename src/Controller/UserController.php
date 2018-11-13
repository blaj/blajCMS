<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserChangeAvatar;
use App\Entity\UserChangeEmail;
use App\Entity\UserChangePassword;
use App\Entity\UserRemindPassword;
use App\Form\UserChangeAvatarType;
use App\Form\UserChangeEmailType;
use App\Form\UserChangePasswordType;
use App\Form\UserRegisterType;
use App\Form\UserRemindPasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     * @IsGranted("ROLE_USER")
     */
    public function index()
    {
        return $this->render('user/index.html.twig');
    }

    /**
     * @Route("/register", name="user_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($this->isGranted('ROLE_USER'))
            return $this->redirectToRoute('user');

        $user = new User();
        $form = $this->createForm(UserRegisterType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPlainPassword()))
                 ->setAvatar('default.png')
                 ->setRegisteredAt(new \DateTime())
                 ->setEmail($user->getPlainEmail())
                 ->setReputation(0);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Konto zostało stworzone pomyślnie!');
            return $this->redirectToRoute('user_register');
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/change/password", name="user_change_password")
     * @IsGranted("ROLE_USER")
     */
    public function change_password(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $userChangePassword = new UserChangePassword();
        $form = $this->createForm(UserChangePasswordType::class, $userChangePassword);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $user->setPassword($passwordEncoder->encodePassword($user, $userChangePassword->getPlainPassword()));

            $manager = $this->getDoctrine()->getManager();
            $manager->merge($user);
            $manager->flush();

            $this->addFlash('success', 'Hasło zostało zmienione pomyślnie!');
            return $this->redirectToRoute('user_change_password');
        }

        return $this->render('user/change_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/change/email", name="user_change_email")
     * @IsGranted("ROLE_USER")
     */
    public function change_email(Request $request)
    {
        $userChangeEmail = new UserChangeEmail();
        $form = $this->createForm(UserChangeEmailType::class, $userChangeEmail);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $user->setEmail($userChangeEmail->getPlainEmail());

            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash('success', 'E-Mail został zmieniony pomyślnie!');
            return $this->redirectToRoute("user_change_email");
        }

        return $this->render('user/change_email.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/change/avatar", name="user_change_avatar")
     * @IsGranted("ROLE_USER")
     */
    public function change_avatar(Request $request)
    {
        $userChangeAvatar = new UserChangeAvatar();
        $form = $this->createForm(UserChangeAvatarType::class, $userChangeAvatar);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $userChangeAvatar->getAvatar();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move(
                $this->getParameter('upload_avatar_directory'),
                $fileName
            );

            $user = $this->getUser();
            $user->setAvatar($fileName);

            $manager = $this->getDoctrine()->getManager();
            $manager->merge($user);
            $manager->flush();

            $this->addFlash('success', 'Avatar został zmieniony pomyślnie!');
            return $this->redirectToRoute("user_change_avatar");
        }

        return $this->render('user/change_avatar.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @ROUTE("/user/show/{id}", name="user_show")
     */
    public function show(User $user)
    {
        return $this->render('user/show.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/user/remindpassword/", name="user_remind_password")
     */
    public function remind_password()
    {
        $userRemindPassword = new UserRemindPassword();
        $form = $this->createForm(UserRemindPasswordType::class, $userRemindPassword);

        return $this->render('user/remind_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
