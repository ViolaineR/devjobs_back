<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\Job;
use App\Form\CandidateType;
use App\Form\JobType;
use App\Repository\CandidateRepository;
use App\Repository\CompanyRepository;
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
    public function new(Request $request, CompanyRepository $companyRepository)
    {
        $job = new Job();
        $job->setCompanies($this->getUser());

        $form = $this->createForm(JobType::class, $job);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $job = $form->getData();

            $this->entityManager->persist($job);
            $this->entityManager->flush();
            return $this->redirectToRoute('job_index');
        }

        return $this->render(
            'job/new.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/job/{id}", name="job_details", requirements={"id"="\d+"})
     */
    public function details(JobRepository $jobRepository, int $id): Response
    {
        $job = $jobRepository->findOneById($id);
        $companyId = $jobRepository->find($id)->getCompanies()->getId();

        return $this->render('job/details.html.twig', [
            'job' => $job,
            'companyId' => $companyId,
        ]);
    }

    /**
     * @Route("/job/{id}/edit", name="job_update", requirements={"id"="\d+"})
     */
    public function update(JobRepository $jobRepository, Job $job, int $id, Request $request): Response
    {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);
        $id = $jobRepository->findOneById($id);
        // $job = $jobRepository->findOneById($id);
        if ($form->isSubmitted() && $form->isValid()) {
            $jobRepository->add($job, true);
            // $job = $form->getData();

            // $this->entityManager->persist($job);
            // $this->entityManager->flush();
            return $this->redirectToRoute('job_details', ['id' => $job->getId()]);
        }

        return $this->render('job/edit.html.twig', [
            'job' => $job,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/job/{id}/apply", name="job_apply", requirements={"id"="\d+"})
     */
    public function apply(CandidateRepository $candidateRepository, JobRepository $jobRepository, int $id, Request $request): Response
    {

        $candidate = new Candidate();
        $job = $jobRepository->find($id);
        $candidate->setJobOffer($job);

        $form = $this->createForm(CandidateType::class, $candidate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $candidateRepository->add($candidate);
            $candidate = $form->getData();

            $this->entityManager->persist($candidate);
            $this->entityManager->flush();


            $this->addFlash('success', 'Votre candidature as bien été prise en compte.');
            return $this->redirectToRoute('job_index');
        }

        return $this->render(
            'job/apply.html.twig',
            [
                'form' => $form->createView(),
                'job' => $job,
                'id' => $id,
            ]
        );
    }

    /**
     * @Route("/job/{id}/candidates", name="job_candidates", requirements={"id"="\d+"})
     */
    public function showCandidates(CandidateRepository $candidateRepository, JobRepository $jobRepository, int $id): Response
    {
        $candidates = $candidateRepository->findCandidatesByJob($id);
        $jobId = $jobRepository->find($id);
        $companyId = $jobRepository->find($id)->getCompanies()->getId();
        $currentId = $this->getUser()->getId();

        if ($companyId == $currentId) {
            return $this->render(
                'job/candidates.html.twig',
                [
                    'candidates' => $candidates,
                    'jobId' => $jobId,
                ]
            );
        } else {
            return $this->redirectToRoute('job_index');
        }
    }
}
