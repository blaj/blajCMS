<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleCategory;
use App\Entity\Comment;
use App\Entity\CommentReputation;
use App\Form\CommentAddType;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @Route("/page/{page}", name="home_page")
     * Głowna akcja odpowiedzialna za wyświetlanie strony głównej wraz ze wszystkimi artykułami
     */
    public function index($page = null)
    {
        $page = $page ?? 1;

        $manager = $this->getDoctrine()->getManager();

        $queryBuilder = $manager->createQueryBuilder()
            ->select('u')
            ->from(Article::class, 'u')
            ->orderBy('u.id', 'DESC');;
        $adapter = new DoctrineORMAdapter($queryBuilder);

        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage(5)
              ->setCurrentPage($page);

        return $this->render('home/index.html.twig', [
            'articles' => $pager,
        ]);
    }

    /**
     * @Route("/article/{id}", name="article_show")
     * Akcja do pokazywania artykułow na podstawie przyjmowanego id artykułu
     */
    public function show(Article $article, Request $request)
    {

        $comment = new Comment();
        $form = $this->createForm(CommentAddType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isGranted('ROLE_USER')) {

                $comment->setArticle($article)
                    ->setUser($this->getUser())
                    ->setCreatedAt(new \DateTime())
                    ->setReputation(0);

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($comment);
                $manager->flush();

                $this->addFlash('success', 'Komentarz został dodany!');
            }

            return $this->redirectToRoute('article_show',['id' => $article->getId()]);
        }

        return $this->render('home/show.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/article/categories/{id}", name="article_categories")
     */
    public function article_categories(ArticleCategory $articleCategory)
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository(Article::class)->findBy(['category' => $articleCategory->getId()]);

        return $this->render('home/categories.html.twig', [
            'articles' => $articles,
            'category' => $articleCategory
        ]);
    }

    /**
     * @Route("/comment/delete/{id}", name="comment_delete")
     * @IsGranted("ROLE_USER")
     * Akcja do usuwania komentarzy na podstawie przyjmowanego id komentarza
     */
    public function comment_delete(Comment $comment)
    {
        if ($comment->getUser() == $this->getUser()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($comment);
            $manager->flush();

            $this->addFlash('success', 'Komentarz zostal prawidłowo usunięty!');
        }

        return $this->redirectToRoute('article_show', ['id' => $comment->getArticle()->getId()]);
    }

    /**
     * @Route("/comment/modify/{id}", name="comment_modify")
     * @IsGranted("ROLE_USER")
     * Akcja do modyfikowania istniejącego komentarza na podstawie przyjmowanego id komentarza
     */
    public function comment_modify(Comment $comment, Request $request)
    {
        if ($comment->getUser() != $this->getUser())
            return $this->redirectToRoute('article_show', ['id' => $comment->getArticle()->getId()]);

        $form = $this->createForm(CommentAddType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($comment-> getUser() == $this->getUser()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->merge($comment);
                $manager->flush();
            }

            $this->addFlash('success', 'Komentarz został prawidłowo zmodyfikowany!');
            return $this->redirectToRoute('article_show', ['id' => $comment->getArticle()->getId()]);
        }

        return $this->render('home/comment_modify.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/comment/rep/add/{id}", name="comment_add_rep")
     * @IsGranted("ROLE_USER")
     * Akcja do dodawania reputacji do komentarza
     */
    public function add_rep(Comment $comment)
    {
        if ($comment->getUser() != $this->getUser()) {
            $comment->setReputation($comment->getReputation()+1);

            $commentRep = new CommentReputation();
            $commentRep->setName('plus')
                       ->setCreatedAt(new \DateTime())
                       ->setUser($this->getUser())
                       ->setComment($comment);

            $manager = $this->getDoctrine()->getManager();
            $manager->merge($comment);
            $manager->persist($commentRep);
            $manager->flush();

            $this->addFlash('success', 'Punkt reputacji został prawidłowo dodany!');
        }

        return $this->redirectToRoute('article_show', ['id' => $comment->getArticle()->getId()]);
    }

    /**
     * @Route("/comment/rep/minus/{id}", name="comment_minus_rep")
     * @IsGranted("ROLE_USER")
     * Akcja do minusowania reputacji w komentarzu
     */
    public function minus_rep(Comment $comment)
    {
        if ($comment->getUser() != $this->getUser()) {
            $comment->setReputation($comment->getReputation()-1);

            $manager = $this->getDoctrine()->getManager();
            $manager->merge($comment);
            $manager->flush();

            $this->addFlash('success', 'Punkt reputacji został prawidłowo odjęty!');
        }

        return $this->redirectToRoute('article_show', ['id' => $comment->getArticle()->getId()]);
    }
}
