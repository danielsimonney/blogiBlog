<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\Category;
use App\Entity\Comment;
use App\Form\CommentaryType;
use App\Repository\BlogPostRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(BlogPostRepository $blogRepository): Response
    {
        $blogs = $blogRepository->findBy(array(),
            ["created_at"=>'DESC'],
            5
        );
        return $this->render('home/index.html.twig', [
            'blogs'=>$blogs,
        ]);
    }


     /**
     * @Route("/author/{id}", name="authorpage")
     */
    public function author(BlogPostRepository $blogRepository,$id,UserRepository $userRepository): Response
    {
        
        $articles = $blogRepository->findBy(
            ["author"=>$id],
            ['created_at' => 'DESC'],
            10
        );
        $user = $userRepository->findOneBy(["id"=>$id]);
        return $this->render('home/lastArticleUser.html.twig', [
            'articles'=>$articles,
            'author'=>$user
        ]);
    }



        /**
     * @Route("/category/{id}", name="categoryPage")
     */
    public function category(BlogPostRepository $blogRepository,$id,Category $category): Response
    {
        var_dump($category->getId());
        $articles = $blogRepository->findByCategories($category);
        var_dump(count($articles));
        foreach ($articles as $key => $value) {
            var_dump("new obj");
            $cat=($value->getCategories());
            // var_dump($cat);
            foreach ($cat as $key => $valuecat) {
                var_dump($valuecat->getName());
            }
        }
        return $this->render('home/category.html.twig', [
            'category'=>$category,
            'blogs'=>$articles
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog_show", methods={"GET","POST"})
     */
    public function show(Request $request,CommentRepository $commentRepository,EntityManagerInterface $manager,BlogPostRepository $blogRepository,BlogPost $blog): Response
    {
        $auth= false;
        $comment = new Comment;
        if(in_array($blog,($blogRepository->findBy(["author"=>$this->getUser()])))){
            $auth = true;
        }
        $comment->setArticle($blog);
        $form = $this->createForm(CommentaryType::class,$comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($comment);
            $manager->flush();
            $id=$blog->getId();
            return $this->redirectToRoute('blog_show',['id'=>$id]);
        }

        $comments = $commentRepository->findBy(
            ["article"=>$blog->getId()],
            ['created_at' => 'ASC'],
            30
        );
       
        return $this->render('home/blog.html.twig', [
            'blog'=>$blog,
            'comments'=>$comments,
            'form'=>$form->createView(),
            'auth'=> $auth
        ]);
    }
}
