<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/admin/task/add", name="add_task")
     */

    public function add()
    {
        return $this->render('task/index.html.twig');
    }
}
