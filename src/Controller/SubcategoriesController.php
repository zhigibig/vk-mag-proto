<?php

namespace App\Controller;

use App\Entity\Subcategories;
use App\Form\SubcategoriesType;
use App\Repository\SubcategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/subcategories')]
class SubcategoriesController extends AbstractController
{
    #[Route('/', name: 'app_subcategories_index', methods: ['GET'])]
    public function index(SubcategoriesRepository $subcategoriesRepository): Response
    {
        return $this->render('subcategories/index.html.twig', [
            'subcategories' => $subcategoriesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_subcategories_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $subcategory = new Subcategories();
        $form = $this->createForm(SubcategoriesType::class, $subcategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($subcategory);
            $entityManager->flush();

            return $this->redirectToRoute('app_subcategories_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('subcategories/new.html.twig', [
            'subcategory' => $subcategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_subcategories_show', methods: ['GET'])]
    public function show(Subcategories $subcategory): Response
    {
        return $this->render('subcategories/show.html.twig', [
            'subcategory' => $subcategory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_subcategories_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Subcategories $subcategory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SubcategoriesType::class, $subcategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_subcategories_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('subcategories/edit.html.twig', [
            'subcategory' => $subcategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_subcategories_delete', methods: ['POST'])]
    public function delete(Request $request, Subcategories $subcategory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subcategory->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($subcategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_subcategories_index', [], Response::HTTP_SEE_OTHER);
    }
}
