<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class EmployeeEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(max:255)]
    #[ORM\Column(type: "string")]
    private string $firstname;

    #[Assert\NotBlank]
    #[Assert\Length(max:255)]
    #[ORM\Column(type: "string")]
    private string $lastname;

    #[Assert\NotBlank]
    #[ORM\Column(type: "datetime")]
    private DateTime $birthday;

    #[Assert\Email]
    #[ORM\Column(type: "string", nullable: true)]
    private ?string $email = null;

    #[Assert\Length(max: 255)]
    #[ORM\Column(type: "string", nullable: true)]
    private ?string $github = null;

    #[Assert\Length(max: 255)]
    #[ORM\Column(type: "string")]
    private string $image = "defaultProfilePicture.png";

    #[Assert\NotBlank]
    #[ORM\Column(type: "date", options: ["default" => "CURRENT_DATE"])]
    private DateTime $atFatchipSince;

    #[ORM\ManyToMany(targetEntity: SkillEntity::class, inversedBy: 'employee_entity', cascade: ['persist'])]
    #[ORM\JoinTable(name: 'employee2pre_skills')]
    private Collection $preSkills;

    #[ORM\ManyToMany(targetEntity: SkillEntity::class, inversedBy: 'employee_entity', cascade: ['persist'])]
    #[ORM\JoinTable(name: 'employee2new_skills')]
    private Collection $newSkills;

    public function __construct()
    {
        $this->atFatchipSince = new DateTime();
        $this->preSkills = new ArrayCollection();
        $this->newSkills = new ArrayCollection();
    }

    public function assign(EmployeeEntity $data, $setImage = true): void
    {
        $this->setFirstname($data->getFirstname());
        $this->setLastname($data->getLastname());
        $this->setBirthday($data->getBirthday());
        $this->setEmail($data->getEmail());
        $this->setGithub($data->getGithub());
        $this->setAtFatchipSince($data->getAtFatchipSince());

        if ($setImage) {
            $this->setImage($data->getImage());
        }

        $this->setPreSkills($data->getPreSkills());
        $this->setNewSkills($data->getNewSkills());
    }
    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return DateTime
     */
    public function getBirthday(): DateTime
    {
        return $this->birthday;
    }

    /**
     * @param DateTime $birthday
     */
    public function setBirthday(DateTime $birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getGithub(): ?string
    {
        return $this->github;
    }

    /**
     * @param string|null $github
     */
    public function setGithub(?string $github): void
    {
        $this->github = $github;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return DateTime
     */
    public function getAtFatchipSince(): DateTime
    {
        return $this->atFatchipSince;
    }

    /**
     * @param DateTime $atFatchipSince
     */
    public function setAtFatchipSince(DateTime $atFatchipSince): void
    {
        $this->atFatchipSince = $atFatchipSince;
    }

    public function getPreSkills(): Collection
    {
        return $this->preSkills;
    }

    public function setPreSkills(Collection $skills): void
    {
        $this->preSkills = $skills;
    }

    public function getNewSkills(): Collection
    {
        return $this->newSkills;
    }

    public function setNewSkills(Collection $skills): void
    {
        $this->newSkills = $skills;
    }

    public function removeDuplicateSkills(): void
    {
        $preSkills = $this->preSkills->toArray();
        $newSkills = $this->newSkills->toArray();

        $this->preSkills = new ArrayCollection(array_unique($preSkills));
        $this->newSkills = new ArrayCollection(array_unique($newSkills));
    }

    public function removeNewSkill(SkillEntity $skill): void
    {
        $this->newSkills->removeElement($skill);
    }

    public function removePreSkill(SkillEntity $skill): void
    {
        $this->preSkills->removeElement($skill);
    }
}