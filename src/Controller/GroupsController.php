<?php

namespace App\Controller;

use App\Entity\Groups;
use App\Form\GroupsType;
use App\Repository\GroupsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/groups')]
class GroupsController extends AbstractController
{
    #[Route('/', name: 'app_groups_index', methods: ['GET'])]
    public function index(GroupsRepository $groupsRepository): Response
    {
        return $this->render('groups/index.html.twig', [
            'groups' => $groupsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_groups_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $group = new Groups();
        $form = $this->createForm(GroupsType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($group);
            $entityManager->flush();

            return $this->redirectToRoute('app_groups_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('groups/new.html.twig', [
            'group' => $group,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_groups_show', methods: ['GET'])]
    public function show(Groups $group): Response
    {
        return $this->render('groups/show.html.twig', [
            'group' => $group,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_groups_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Groups $group, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GroupsType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_groups_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('groups/edit.html.twig', [
            'group' => $group,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_groups_delete', methods: ['POST'])]
    public function delete(Request $request, Groups $group, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$group->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($group);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_groups_index', [], Response::HTTP_SEE_OTHER);
    }
}
