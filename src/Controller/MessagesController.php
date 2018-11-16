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

/**
 * @IsGranted("ROLE_USER")
 */
class MessagesController extends AbstractController
{
    /**
     * @Route("/messages/received", name="messages_received")
     */
    public function received()
    {
        return $this->render('messages/received.html.twig');
    }

    /**
     * @Route("/messages/sent", name="messages_sent")
     */
    public function sent()
    {
        return $this->render('messages/sent.html.twig');
    }

    /**
     * @Route("/messages/read/{id}", name="messages_read")
     */
    public function read(MessageTopic $messageTopic, Request $request)
    {
        if ($messageTopic->getToUser()->getId() == $this->getUser()->getId() || $messageTopic->getFromUser()->getId() == $this->getUser()->getId()) {

            $manager = $this->getDoctrine()->getManager();

            if ($messageTopic->getFromUser() == $this->getUser())
                $messageTopic->setReadedFromUser(true);

            if ($messageTopic->getToUser() == $this->getUser())
                $messageTopic->setReadedToUser(true);

            $manager->merge($messageTopic);
            $manager->flush();

            $message = new Message();
            $form = $this->createForm(MessageSendType::class, $message);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                if ($messageTopic->getFromUser() == $this->getUser())
                    $messageTopic->setReadedToUser(false)
                                 ->setReadedFromUser(true);
                else
                    $messageTopic->setReadedToUser(true)
                                 ->setReadedFromUser(false);

                $message->setTopic($messageTopic)
                    ->setSendAt(new \DateTime())
                    ->setTitle($messageTopic->getTitle())
                    ->setUser($this->getUser());

                $manager->merge($messageTopic);

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
                         ->setToUser($toUser)
                         ->setReadedToUser(false)
                         ->setReadedFromUser(true);

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

    /**
     * @Route("/messages/notifications", name="messages_notifications")
     */
    public function notifications()
    {
        //TODO: Po otworzeniu nieprzeczytane powiadiomienia zmieniają sie na przeczytane!


        return $this->render('messages/notifications.html.twig');
    }
}
