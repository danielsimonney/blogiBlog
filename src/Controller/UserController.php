<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Form\AddBlogType;
use App\Repository\BlogPostRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;

class UserController extends AbstractController
{
/**
     * @Route("/user/article/new", name="new_blog", methods={"GET","POST"})
     */

    public function new(Request $request,EntityManagerInterface $manager)
    {
        $blog = new BlogPost;
        $user = $this->getUser();
        $blog->setAuthor($user);
        $form = $this->createForm(AddBlogType::class,$blog);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($blog);
            $manager->flush();
            $id=$blog->getId();
            return $this->redirectToRoute('new_blog');
        }
        return $this->render('user/newblog.html.twig', [
            'form' => $form->createView()
        ]);
     }


    /**
     * @Route("user/comment/supress/{id}", name="comment_supress",methods={"DELETE"})
     */
    public function supressTicket(Request $request,Comment $comment): Response
    {        
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($comment);
        $entityManager->flush();
        }
        return $this->redirectToRoute('homepage');
    }

     /**
     * @Route("/user/me", name="me", methods={"GET"})
     */

     public function me(Request $request,EntityManagerInterface $manager,CommentRepository $commentRepository,BlogPostRepository $blogRepository)
     {
         $user=$this->getUser();
         $userBlogs = $blogRepository->findBy(["author"=>$user]);
         $commentByBlog = [];
         $lastComments = [];
         foreach ($userBlogs as $value) {
            $commentByArticle=$commentRepository->findBy(["article"=>$value->getId()]);
                array_push($commentByBlog,$commentByArticle);
         }
         foreach ($commentByBlog as $value) {
             foreach ($value as $comment) {
                 array_push($lastComments,$comment);
             }
         }


         usort($lastComments, function($a, $b) {
            return $b->getCreatedAt()->format('U') - $a->getCreatedAt()->format('U');
        });


        usort($userBlogs, function($a, $b) {
            return $b->getCreatedAt()->format('U') - $a->getCreatedAt()->format('U');
        });

        $blogs = array_slice($userBlogs, 0, 5);

        $comments = array_slice($lastComments, 0, 10);

         return $this->render('user/me.html.twig', [
            'user' => $user,
            'blogs' => $blogs,
            'comments' => $comments
        ]);

     }     

}
