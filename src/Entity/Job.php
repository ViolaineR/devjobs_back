<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JobRepository::class)
 */
class Job
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contract;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text")
     */
    private $profileDescription;

    /**
     * @ORM\Column(type="text")
     */
    private $profileSkills;

    /**
     * @ORM\Column(type="text")
     */
    private $jobDescription;

    /**
     * @ORM\Column(type="text")
     */
    private $jobMissions;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $website;

    /**
     * @ORM\OneToMany(targetEntity=Candidate::class, mappedBy="jobOffer", orphanRemoval=true)
     */
    private $candidates;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="jobs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $companies;

    public function __construct()
    {
        $this->candidates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getContract(): ?string
    {
        return $this->contract;
    }

    public function setContract(string $contract): self
    {
        $this->contract = $contract;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getProfileDescription(): ?string
    {
        return $this->profileDescription;
    }

    public function setProfileDescription(string $profileDescription): self
    {
        $this->profileDescription = $profileDescription;

        return $this;
    }

    public function getProfileSkills(): ?string
    {
        return $this->profileSkills;
    }

    public function setProfileSkills(string $profileSkills): self
    {
        $this->profileSkills = $profileSkills;

        return $this;
    }

    public function getJobDescription(): ?string
    {
        return $this->jobDescription;
    }

    public function setJobDescription(string $jobDescription): self
    {
        $this->jobDescription = $jobDescription;

        return $this;
    }

    public function getJobMissions(): ?string
    {
        return $this->jobMissions;
    }

    public function setJobMissions(string $jobMissions): self
    {
        $this->jobMissions = $jobMissions;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(string $website): self
    {
        $this->website = $website;

        return $this;
    }

    /**
     * @return Collection<int, Candidate>
     */
    public function getCandidates(): Collection
    {
        return $this->candidates;
    }

    public function addCandidate(Candidate $candidate): self
    {
        if (!$this->candidates->contains($candidate)) {
            $this->candidates[] = $candidate;
            $candidate->setJobOffer($this);
        }

        return $this;
    }

    public function removeCandidate(Candidate $candidate): self
    {
        if ($this->candidates->removeElement($candidate)) {
            // set the owning side to null (unless already changed)
            if ($candidate->getJobOffer() === $this) {
                $candidate->setJobOffer(null);
            }
        }

        return $this;
    }

    public function getCompanies(): ?Company
    {
        return $this->companies;
    }

    public function setCompanies(?Company $company): self
    {
        $this->companies = $company;

        return $this;
    }
}
