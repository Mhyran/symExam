<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/project/all", name="api_all_projects", methods={"GET"})
     */
    public function projects(SerializerInterface $serializer, NormalizerInterface $normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Project::class);
        $projects = $repository->findAll();

        $serialized_projects = $serializer->serialize($projects, 'json', ['groups' => ['project']]);
        return new JsonResponse($serialized_projects, 200, [], true);
    }


}
