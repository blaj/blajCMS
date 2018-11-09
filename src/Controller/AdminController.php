<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleAddNew;
use App\Form\ArticleAddNewType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    //TODO: Zrobić panel administratora i bailando

    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/admin/articles", name="admin_articles")
     */
    public function articles()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository(Article::class)->findAll();

        return $this->render('admin/articles.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/admin/articles/delete/{id}", name="admin_articles_delete")
     */
    public function articles_delete(Article $article)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        $this->addFlash('success', 'Artykuł został usunięty!');
        return $this->redirectToRoute('admin_articles');
    }

    /**
     * @Route("/admin/articles/add", name="admin_articles_add")
     */
    public function articles_add(Request $request)
    {
        $articleAddNew = new ArticleAddNew();
        $form = $this->createForm(ArticleAddNewType::class, $articleAddNew);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $articleAddNew->getImage();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move(
                $this->getParameter('upload_article_directory'),
                $fileName
            );

            $article = new Article();
            $article->setTitle($articleAddNew->getTitle())
                    ->setContent($articleAddNew->getContent())
                    ->setCreatedAt(new \DateTime())
                    ->setUser($this->getUser())
                    ->setImage($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'Artykuł został usunięty!');
            return $this->redirectToRoute('admin_articles');
        }

        return $this->render('admin/articles_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/articles/modify/{id}", name="admin_articles_modify")
     */
    public function articles_modify(Article $article, Request $request)
    {
        $articleModify = new ArticleAddNew();
        $articleModify->setTitle($article->getTitle())
                      ->setContent($article->getContent());

        $form = $this->createForm(ArticleAddNewType::class, $articleModify);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $articleModify->getImage();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move(
                $this->getParameter('upload_article_directory'),
                $fileName
            );

            $article->setTitle($articleModify->getTitle())
                    ->setContent($articleModify->getContent())
                    ->setImage($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->merge($article);
            $em->flush();

            $this->addFlash('success', 'Artykuł został zmodyfikowany!');
            return $this->redirectToRoute('admin_articles');
        }

        return $this->render('admin/articles_modify.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
