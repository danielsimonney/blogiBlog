<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/category/new", name="new_category")
     */
    public function index(Request $request,EntityManagerInterface $manager)
    {
        $category = new Category;
        $form = $this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($category);
            $manager->flush();
            return $this->redirectToRoute('homepage');
        }
        return $this->render('admin/index.html.twig', [
            'form'=>$form->createView(),
        ]);
    }
}
