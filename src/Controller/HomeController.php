<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'home')]
    public function index(TaskRepository $taskRepository)
    {
        $tasks = $taskRepository->findAll();

        return $this->render('home.html.twig',
            [
                'tasks' => $tasks,
            ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($task);
            $this->em->flush();

            return $this->redirectToRoute('home');
        }


        return $this->render('add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}