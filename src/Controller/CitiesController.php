<?php

namespace App\Controller;

use App\Entity\Cities;
use App\Form\CitiesType;
use App\Repository\CitiesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cities')]
class CitiesController extends AbstractController
{
    #[Route('/', name: 'app_cities_index', methods: ['GET'])]
    public function index(CitiesRepository $citiesRepository): Response
    {
        return $this->render('cities/index.html.twig', [
            'cities' => $citiesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cities_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $city = new Cities();
        $form = $this->createForm(CitiesType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($city);
            $entityManager->flush();

            return $this->redirectToRoute('app_cities_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cities/new.html.twig', [
            'city' => $city,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cities_show', methods: ['GET'])]
    public function show(Cities $city): Response
    {
        return $this->render('cities/show.html.twig', [
            'city' => $city,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cities_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cities $city, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CitiesType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cities_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cities/edit.html.twig', [
            'city' => $city,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cities_delete', methods: ['POST'])]
    public function delete(Request $request, Cities $city, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$city->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($city);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cities_index', [], Response::HTTP_SEE_OTHER);
    }
}
