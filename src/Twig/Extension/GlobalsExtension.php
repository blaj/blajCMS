<?php

namespace App\Twig\Extension;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

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
}