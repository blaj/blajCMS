<?php

namespace App\Twig\Extension;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\MessageTopic;
use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\TwigFunction;

class GlobalsExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function getGlobals() {
        return array(
            'lastComments' => $this->getLastComments(),
            'lastRegisterUser' => $this->getLastResgiterUser(),
            'countUsers' => $this->getCountUsers(),
            'countArticles' => $this->getCountArticles(),
            'countComments' => $this->getCountComments()
        );
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('isGotNotReadedMessages', array($this, 'isGotNotReadedMessages')),
            new TwigFunction('isGotNotReadedToUserMessages', array($this, 'isGotNotReadedToUserMessages')),
            new TwigFunction('isGotNotReadedFromUserMessages', array($this, 'isGotNotReadedFromUserMessages')),
            new TwigFunction('isGotNoReadedNotifications', array($this, 'isGotNoReadedNotifications'))

        );
    }

    private function getLastComments()
    {
        $comments = $this->manager->getRepository(Comment::class)->findBy(array(), array('id' => 'DESC'), 5);
        return $comments;
    }

    private function getLastResgiterUser()
    {
        $lastUser = $this->manager->getRepository(User::class)->findOneBy(array(), array('id' => 'DESC'));
        return $lastUser;
    }

    private function getCountUsers()
    {
        $countUsers = count($this->manager->getRepository(User::class)->findAll());
        return $countUsers;
    }

    private function getCountArticles()
    {
        $countArticles = count($this->manager->getRepository(Article::class)->findAll());
        return $countArticles;
    }

    private function getCountComments()
    {
        $countComments = count($this->manager->getRepository(Comment::class)->findAll());
        return $countComments;
    }

    public function isGotNotReadedMessages(UserInterface $user)
    {
        $isNotReadedToUserMessages = $this->manager->getRepository(MessageTopic::class)->findOneBy(array('toUser' => $user, 'readedToUser' => false));
        $isNotReadedFromUserMessages = $this->manager->getRepository(MessageTopic::class)->findOneBy(array('fromUser' => $user, 'readedFromUser' => false));

        $isNotReadedNotifications = $this->manager->getRepository(Notification::class)->findOneBy(array('user' => $user, 'readed' => false));

        if ($isNotReadedToUserMessages || $isNotReadedFromUserMessages || $isNotReadedNotifications)
            return true;
        else
            return false;
    }

    public function isGotNotReadedToUserMessages(UserInterface $user)
    {
        $isNotReaded = $this->manager->getRepository(MessageTopic::class)->findOneBy(array('toUser' => $user, 'readedToUser' => false));

        if ($isNotReaded)
            return true;
        else
            return false;
    }

    public function isGotNotReadedFromUserMessages(UserInterface $user)
    {
        $isNotReaded = $this->manager->getRepository(MessageTopic::class)->findOneBy(array('fromUser' => $user, 'readedFromUser' => false));

        if ($isNotReaded)
            return true;
        else
            return false;
    }

    public function isGotNoReadedNotifications(UserInterface $user)
    {
        $isNoReadedNotifications = $this->manager->getRepository(Notification::class)->findOneBy(array('user' => $user, 'readed' => false));

        if ($isNoReadedNotifications)
            return true;
        else
            return false;
    }
}