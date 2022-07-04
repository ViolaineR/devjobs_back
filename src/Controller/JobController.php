<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\JobType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/job", name="job_index")
     */
    public function index(): Response
    {
        $jobs = $this->entityManager->getRepository(Job::class)->findOneByTitle('Developpeur Front-end');

        return $this->render('job/index.html.twig', [
            'controller_name' => 'JobController',
        ]);
    }

    /**
     * @Route("/job/new", name="new_job")
     */
    public function new(Request $request)
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $job = $form->getData();

            $this->entityManager->persist($job);
            $this->entityManager->flush();
        }

        return $this->render(
            'job/new.html.twig',
            ['form' => $form->createView()]
        );
    }
}
