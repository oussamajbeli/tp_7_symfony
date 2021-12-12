<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// use Symfony\Component\HttpFounation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


use App\Entity\CategorySearch;
use App\Form\CategorySearchType;

// use App\Controller\Article;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\PropertySearch;
use App\Form\ArticleType as FormArticleType;
use App\Form\CategoryType;
use App\Form\PropertySearchType;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="article_list")
     */
    public function index(Request $request)
    {
        $propertySearch = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $propertySearch);
        $form->handleRequest($request);

        $articles = [];
        $nom = $propertySearch->getNom();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($nom != "") {
                $name = $propertySearch->getNom();
                $articles = $this->getDoctrine()->getRepository(Article::class)->findBy(['Nom' => $name]);
            } else {
                $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
            }
        }

        // $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        return $this->render("index.html.twig", ['form' => $form->createView(), 'articles' => $articles]);
    }









    /**
     * @Route("/category/newCat", name="new_category")
     * Method({"GET","POST"})
     */
    public function newCategory(Request $request)
    {

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);

            $entityManager->flush();
            return $this->redirectToRoute('category');
        }

        return $this->render('category/newCategory.html.twig', [
            'form' => $form->createView()
        ]);
    }




    /**
     * @Route("/article/save")
     */
    public function save()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $article = new Article();
        $article->setNom('Article3');
        $article->setPrix(355);

        $entityManager->persist($article);
        $entityManager->flush();

        return new Response('Article enregistré avec id  : ' . $article->getId());
    }

    /**
     * @Route("/article/new",name="new_article")
     * Method({"GET","POST"})
     */

    public function new(Request $request)
    {

        $article = new Article();

        $form = $this->createForm(
            FormArticleType::class,
            $article
        );
        //     ->add('nom', TextType::class)
        //     ->add('prix', TextType::class)
        //     ->add('save', SubmitType::class, array('label' => 'Créer'))->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            // return new Response('Article enregistré avec id  ' . $article->getAll());
            return $this->redirectToRoute('article_list');
        }
        return $this->render('article/new.html.twig', ['form' => $form->createView()]);
    }













    /**
     * @Route("/article/{id}",name="article_show")
     */

    public function show($id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        return $this->render('article/show.html.twig', array('article' => $article));
    }


    /**
     * @Route("/article/edit/{id}",name="edit_article")
     * Method({"GET","POST"})
     */

    public function edit(Request $request, $id)
    {
        $article = new Article();
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $form = $this->createForm(FormArticleType::class, $article);
        // ->add('nom', TextType::class);
        // ->add('prix', TextType::class)
        // ->add('save', submitType::class, array(
        //     'label' => 'Modifier'
        // ))->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('article_list');
        }

        return $this->render('article/edit.html.twig', ['form' => $form->createView()]);
    }






    /**
     *@Route("/categorysearch",name="art_cat")
     * Method({"GET","POST"})
     */
    public function articlesparcategory(Request $request)
    {
        $categorySearch = new CategorySearch();
        $form = $this->createForm(CategorySearchType::class, $categorySearch);

        $form->handleRequest($request);

        $articles = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $categorySearch()->getCategory();

            if ($category != "") {
                $articles = $category->getArticles();
            } else {
                $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
            }
            return $this->render('article/articlesparcategory.html.twig', ['form' => $form->createView(), 'articles' => $articles]);
        }
    }
}
