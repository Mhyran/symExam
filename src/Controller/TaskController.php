<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/admin/task/add/{id}", name="add_task")
     */

    public function add($id, Request $request, EntityManagerInterface $entityManager)
    {
        $task = new Task();
        $repository = $this->getDoctrine()->getRepository(Project::class);
        $project = $repository->findOneBy(['id' => $id]);
        $form = $this->createFormBuilder($task)
            ->add('title')
            ->add('description')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setCreatedAt(new \DateTime());
            $task->setProjectId($project);
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('project_list');
        }

        return $this->render('task/index.html.twig',[
            'project' => $project,
            'formTask' => $form->createView()
        ]);
    }
}
