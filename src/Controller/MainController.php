<?php

namespace App\Controller;

use App\Entity\Crud;
use App\Form\CreateFormType;
use App\Repository\CrudRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    private $em;
    private $crudRepository;
    public function __construct(CrudRepository $crudRepository, EntityManagerInterface $em){
        $this->crudRepository=$crudRepository;
        $this->em=$em;
    }
    #[Route('/crud', name: 'app_main')]
    public function index(): Response
    {
        $records = $this->crudRepository->findAll();
        return $this->render('crud/index.html.twig', [
            'records' => $records
        ]);
    }


    #[Route('/crud/show/{id}', methods: ['GET'], name: 'show')]
    public function show($id): Response
    {
        $record = $this->crudRepository->find($id);
        return $this->render('crud/show.html.twig', [
            'record' => $record
        ]);
    }

    #[Route('/crud/create', name: 'create')]
    public function create(Request $request): Response
    {
        $record = new Crud();
        $form = $this->createForm(CreateFormType::class, $record);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $newRecord=$form->getData();
            $this->em->persist($newRecord);
            $this->em->flush();

            return $this->redirectToRoute('app_main');
        }
        return $this->render('crud/create.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    #[Route('/crud/edit/{id}', name: 'edit')]
    public function edit($id, Request $request): Response
    {
        $record= $this->crudRepository->find($id);
        $form = $this->createForm(CreateFormType::class, $record);
        $form->handleRequest($request);
        if($form->isSubmitted()  && $form->isValid()){
            $record->setHeader($form->get('header')->getData());
            $record->setContent($form->get('content')->getData());
            $this->em->persist($record);
            $this->em->flush();

            return  $this->redirectToRoute('app_main');
        }

        return $this->render('crud/edit.html.twig', [
            'record'=>$record,
            'form'=>$form->createView()
        ]);
    }

    #[Route('/crud/delete/{id}', methods: ['GET', 'DELETE'], name: 'delete')]
    public function delete($id): Response
    {
        $record = $this->crudRepository->find($id);
        $this->em->remove($record);
        $this->em->flush();
        return $this->redirectToRoute('app_main');
    }
}
