<?php

namespace App\Controller;

use App\Entity\Warehouses;
use App\Form\WarehousesType;
use App\Repository\WarehousesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/warehouses')]
class WarehousesController extends AbstractController
{
    #[Route('/', name: 'app_warehouses_index', methods: ['GET'])]
    public function index(WarehousesRepository $warehousesRepository): Response
    {
        return $this->render('warehouses/index.html.twig', [
            'warehouses' => $warehousesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_warehouses_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $warehouse = new Warehouses();
        $form = $this->createForm(WarehousesType::class, $warehouse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($warehouse);
            $entityManager->flush();

            return $this->redirectToRoute('app_warehouses_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('warehouses/new.html.twig', [
            'warehouse' => $warehouse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_warehouses_show', methods: ['GET'])]
    public function show(Warehouses $warehouse): Response
    {
        return $this->render('warehouses/show.html.twig', [
            'warehouse' => $warehouse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_warehouses_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Warehouses $warehouse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WarehousesType::class, $warehouse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_warehouses_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('warehouses/edit.html.twig', [
            'warehouse' => $warehouse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_warehouses_delete', methods: ['POST'])]
    public function delete(Request $request, Warehouses $warehouse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$warehouse->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($warehouse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_warehouses_index', [], Response::HTTP_SEE_OTHER);
    }
}
