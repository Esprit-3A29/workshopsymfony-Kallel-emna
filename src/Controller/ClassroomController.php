<?php

namespace App\Controller;

use App\Form\ClassroomType;
use App\Repository\ClassroomRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Classroom;
use Symfony\Component\HttpFoundation\Request;


class ClassroomController extends AbstractController
{
    #[Route('/classroom', name: 'app_classroom')]
    public function index(): Response
    {
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }
    
    #[Route('/classrooms', name: 'list_classroom')]
    public function listclassroom(ClassroomRepository $repository)
    {
        $classrooms=$repository->findAll();
        return $this->render("classroom/listClassroom.html.twig", array("tabClassroom"=>$classrooms));
    }

    #[Route('/addclassroom', name: 'add_classroom')]
    public function addClassroom(ManagerRegistry $doctrine, Request $request)
    {
        $classroom=new Classroom;
        $form=$this->createForm(ClassroomType::class,$classroom);
        $form->handleRequest($request) ;
        if ($form->isSubmitted()){
             $em= $doctrine->getManager();
             $em->persist($classroom);
             $em->flush();
             return  $this->redirectToRoute("list_classroom");
         }
        return $this->renderForm("classroom/add.html.twig",array("formclassroom"=>$form));
    }

    #[Route('/updateclassroom', name: 'update_classroom')]
    public function updateClassroom($id,ClassroomRepository $repository,ManagerRegistry $doctrine,Request $request)
    {
        $classroom= $repository->find($id);
        $form=$this->createForm(ClassroomType::class,$classroom);
        $form->handleRequest($request) ;
        if ($form->isSubmitted()){
            $em= $doctrine->getManager();
            $em->flush();
            return  $this->redirectToRoute("list_classroom");
        }
        return $this->renderForm("classroom/update.html.twig",array("formClassroom"=>$form));
    }

    #[Route('/removeForm/{id}', name: 'remove')]
    public function removeClassroom($id, ClassroomRepository $repository,ManagerRegistry $doctrine)
    {
        $classroom =$repository->find($id);
        $em=$doctrine->getManager();
        $em->remove($classroom);
        $em->flush();
        return $this->redirectToRoute("list_classroom");
    }
}
