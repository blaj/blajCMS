<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\MessageNew;
use App\Entity\MessageTopic;
use App\Entity\User;
use App\Form\MessageNewType;
use App\Form\MessageSendType;
use App\Form\MessageTopicSendType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MessagesController extends AbstractController
{
    /**
     * @Route("/messages/received", name="messages_received")
     * @IsGranted("ROLE_USER")
     */
    public function received()
    {
        return $this->render('messages/received.html.twig');
    }

    /**
     * @Route("/messages/sent", name="messages_sent")
     * @IsGranted("ROLE_USER")
     */
    public function sent()
    {
        return $this->render('messages/sent.html.twig');
    }

    /**
     * @Route("/messages/read/{id}", name="messages_read")
     * @IsGranted("ROLE_USER")
     */
    public function read(MessageTopic $messageTopic, Request $request)
    {
        if ($messageTopic->getToUser()->getId() == $this->getUser()->getId() || $messageTopic->getFromUser()->getId() == $this->getUser()->getId()) {

            // TODO: Zabezpiecznie, że tylko odbiorca i nadawca mogą czytać wiadomość
            $message = new Message();
            $form = $this->createForm(MessageSendType::class, $message);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $message->setTopic($messageTopic)
                    ->setSendAt(new \DateTime())
                    ->setTitle($messageTopic->getTitle())
                    ->setUser($this->getUser());

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($message);
                $manager->flush();

                $this->addFlash('success', 'Wiadomość została wysłana!');
                return $this->redirectToRoute('messages_read', ['id' => $messageTopic->getId()]);
            }

            return $this->render('messages/read.html.twig', [
                'topic' => $messageTopic,
                'form' => $form->createView()
            ]);
        } else {
            return $this->redirectToRoute('messages_received');
        }
    }

    /**
     * @Route("/messages/send", name="messages_send")
     * @IsGranted("ROLE_USER")
     */
    public function send(Request $request)
    {
        $messageNew = new MessageNew();
        $form = $this->createForm(MessageNewType::class, $messageNew);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();

            $toUser = $manager->getRepository(User::class)->findOneBy(['username' => $messageNew->getToUser()]);

            $messageTopic = new MessageTopic();
            $messageTopic->setTitle($messageNew->getTitle())
                         ->setContent($messageNew->getContent())
                         ->setSendAt(new \DateTime())
                         ->setFromUser($this->getUser())
                         ->setToUser($toUser);

            $manager->persist($messageTopic);

            $message = new Message();
            $message->setTopic($messageTopic)
                    ->setTitle($messageTopic->getTitle())
                    ->setContent($messageTopic->getContent())
                    ->setSendAt($messageTopic->getSendAt())
                    ->setUser($messageTopic->getFromUser());

            $manager->persist($message);

            $manager->flush();

            $this->addFlash('success', 'Wiadomość została wysłana!');
            return $this->redirectToRoute('messages_read', ['id' => $messageTopic->getId()]);
        }

        return $this->render('messages/send.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
