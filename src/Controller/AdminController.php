<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleAddNew;
use App\Entity\ArticleCategory;
use App\Entity\ArticleCategoryAddNew;
use App\Entity\User;
use App\Form\ArticleAddNewType;
use App\Form\ArticleCategoryAddNewType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Category;
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
                    ->setImage($fileName)
                    ->setCategory($articleAddNew->getCategory());

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
                      ->setContent($article->getContent())
                      ->setCategory($article->getCategory());

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

    /**
     * @Route("/admin/categories", name="admin_categories")
     */
    public function categories()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository(ArticleCategory::class)->findAll();

        return $this->render('admin/categories.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/categories/add", name="admin_categories_add")
     */
    public function categories_add(Request $request)
    {
        $categoryAddNew = new ArticleCategoryAddNew();
        $form = $this->createForm(ArticleCategoryAddNewType::class, $categoryAddNew);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = new ArticleCategory();
            $category->setTitle($categoryAddNew->getTitle())
                     ->setDescription($categoryAddNew->getDescription());

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Kategoria artykułów została dodana!');
            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('admin/categories_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/categories/modify/{id}", name="admin_categories_modify")
     */
    public function categories_modify(ArticleCategory $articleCategory, Request $request)
    {
        $categoryModify = new ArticleCategoryAddNew();
        $categoryModify->setTitle($articleCategory->getTitle())
                       ->setDescription($articleCategory->getDescription());

        $form = $this->createForm(ArticleCategoryAddNewType::class, $categoryModify);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $articleCategory->setTitle($categoryModify->getTitle())
                            ->setDescription($categoryModify->getDescription());

            $em = $this->getDoctrine()->getManager();
            $em->merge($articleCategory);
            $em->flush();

            $this->addFlash('success', 'Kategoria artykułów została zmodyfikowana!');
            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('admin/categories_modify.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function users()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('admin/users.html.twig', [
            'users' => $users
        ]);
    }
}
