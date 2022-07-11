<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\JobType;
use App\Repository\JobRepository;
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
        $jobs = $this->entityManager->getRepository(Job::class)->findAll();

        return $this->render('job/index.html.twig', [
            'controller_name' => 'JobController',
            'jobs' => $jobs,
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

    /**
     * @Route("/job/{id}", name="job_details", requirements={"id"="\d+"})
     */
    public function details(JobRepository $jobRepository, int $id): Response
    {
        $job = $jobRepository->findOneById($id);

        return $this->render('job/details.html.twig', [
            'job' => $job
        ]);
    }
}
