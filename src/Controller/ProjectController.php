<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * @Route("/admin/project", name="project_list")
     */
    public function list()
    {
        $repository = $this->getDoctrine()->getRepository(Project::class);

        $projects = $repository->findAll();

        return $this->render('project/index.html.twig', [
            'controller_name' => 'ProjectController',
            'projects' => $projects
        ]);
    }

    /**
     * @Route("/admin/project/add", name="add_project")
     */
    public function add(Request $request, EntityManagerInterface $entityManager)
    {
        $project = new Project();

        $form = $this->createFormBuilder($project)
            ->add('name')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project->setStartedAt(new \DateTime());
            $project->setStatut('Nouveau');

            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('project_list');
        }

        return $this->render('project/add.html.twig',[
            'formProject' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/project/edit/{id}", name="edit_project")
     */
    public function edit(Request $request, $id, EntityManagerInterface $entityManager)
    {
        $repository = $this->getDoctrine()->getRepository(Project::class);
        $project = $repository->findOneBy(['id' => $id]);

        $taskRepository = $this->getDoctrine()->getRepository(Task::class);
        $tasks = $taskRepository->findBy(['project' => $id]);

        if ($request->request->get('edit')){
            $statut = $request->request->get('statut');
            $project->setStatut($statut);
            if ($statut === 'Terminée'){
                $project->setEndedAt(new \DateTime());
            }
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('project_list');
        }

        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'tasks' => $tasks
        ]);
    }
}
