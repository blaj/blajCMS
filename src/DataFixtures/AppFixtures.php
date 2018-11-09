<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Message;
use App\Entity\MessageTopic;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('pl_PL');

        $user = new User();
        $user->setUsername('blaj')
             ->setRoles(['ROLE_ADMIN'])
             ->setPassword($this->passwordEncoder->encodePassword($user, 'test'))
             ->setEmail('test@test.pl')
             ->setAvatar('default.png')
             ->setRegisteredAt(new \DateTime())
             ->setReputation(0);

        $manager->persist($user);

        $user2 = new User();
        $user2->setUsername('testowy')
              ->setPassword($this->passwordEncoder->encodePassword($user, 'test'))
              ->setEmail('test@test2.pl')
              ->setAvatar('default.png')
              ->setRegisteredAt(new \DateTime())
              ->setReputation(0);

        $manager->persist($user2);

        for ($i = 0; $i < mt_rand(3, 6); $i++) {
            $messageTopic = new MessageTopic();
            $messageTopic->setTitle($faker->sentence)
                         ->setSendAt(new \DateTime())
                         ->setFromUser($user)
                         ->setToUser($user2);

            $manager->persist($messageTopic);

            for ($j = 0; $j < mt_rand(2, 5); $j++) {
                $message = new Message();
                $message->setTopic($messageTopic)
                        ->setTitle($messageTopic->getTitle())
                        ->setContent(join($faker->paragraphs(2)))
                        ->setSendAt(new \DateTime())
                        ->setUser($user);

                $manager->persist($message);

                $message = new Message();
                $message->setTopic($messageTopic)
                    ->setTitle($messageTopic->getTitle())
                    ->setContent(join($faker->paragraphs(2)))
                    ->setSendAt(new \DateTime())
                    ->setUser($user2);

                $manager->persist($message);
            }
        }

        for ($i = 0; $i < mt_rand(3, 6); $i++) {
            $messageTopic = new MessageTopic();
            $messageTopic->setTitle($faker->sentence)
                ->setSendAt(new \DateTime())
                ->setFromUser($user2)
                ->setToUser($user);

            $manager->persist($messageTopic);

            for ($j = 0; $j < mt_rand(2, 5); $j++) {
                $message = new Message();
                $message->setTopic($messageTopic)
                    ->setTitle($messageTopic->getTitle())
                    ->setContent(join($faker->paragraphs(2)))
                    ->setSendAt(new \DateTime())
                    ->setUser($user2);

                $manager->persist($message);

                $message = new Message();
                $message->setTopic($messageTopic)
                    ->setTitle($messageTopic->getTitle())
                    ->setContent(join($faker->paragraphs(2)))
                    ->setSendAt(new \DateTime())
                    ->setUser($user);

                $manager->persist($message);
            }
        }

        for ($i = 0; $i < 100; $i++) {
            $article = new Article();
            $article->setTitle($faker->sentence)
                    ->setContent(join($faker->paragraphs(6)))
                    ->setCreatedAt(new \DateTime())
                    ->setImage('default.png')
                    ->setUser($user);

            $manager->persist($article);

            for ($j = 0; $j < mt_rand(3, 6); $j++) {
                $comment = new Comment();
                $comment->setContent(join($faker->paragraphs(2)))
                        ->setCreatedAt(new \DateTime())
                        ->setUser($user)
                        ->setArticle($article)
                        ->setReputation(mt_rand(-10, 10));

                $manager->persist($comment);
            }
        }

        $manager->flush();
    }
}
