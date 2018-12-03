<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Users
 *
 * @ORM\Table(name="`user`")
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups("user_info")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=15, nullable=true)
     * @Assert\Length(
     *     min="2",
     *     max="15"
     * )
     * @Groups("user_info")
     */
    private $firstName;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     * @Groups("user_info")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=20, nullable=true)
     *
     * @Assert\Length(
     *     min="2",
     *     max="20"
     * )
     * @Groups("user_info")
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, unique=true)
     * @Assert\Length(
     *     min="2",
     *     max="50"
     * )
     * @Assert\Email()
     * @Assert\NotBlank()
     * @Groups("user_info")
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth_date", type="date", nullable=true)
     * @Groups("user_info")
     */
    private $birthDate;

    /**
     * @var string
     *
     * @ORM\Column(name="about", type="text", nullable=true)
     * @Groups("user_info")
     */
    private $about;

    /**
     * @Assert\Length(max=4096)
     *
     * @Assert\NotBlank()
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=30, unique=true)
     * @Assert\Length(
     *     min="3",
     *     max="30"
     * )
     *
     * @Assert\NotBlank()
     * @Groups("user_info")
     */
    private $username;

    /**
     * @ORM\Column(name="roles", type="json_array", nullable=true)
     * @Groups("user_info")
     */
    private $roles;

    public function __construct()
    {
        $this->createdAt ?? $this->createdAt = new \DateTime('now');
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName(string $firstName): User
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getRoles()
    {
        return array_unique(array_merge(['ROLE_USER'], $this->roles));
    }

    public function eraseCredentials()
    {
    }

    public function getSalt()
    {
        return null;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName(string $lastName): User
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function isAdmin(): bool
    {
        return in_array('ROLE_ADMIN', $this->roles);
    }

    /**
     * @param $username
     * @return $this
     */
    public function setUsername($username): User
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }


    /**
     * @param $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }


    /**
     * @param $plainPassword
     * @return $this
     */
    public function setPlainPassword(string $plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return string
     */
    public function getAbout(): ?string
    {
        return $this->about;
    }


    /**
     * @param string $about
     * @return User
     */
    public function setAbout(string $about): User
    {
        $this->about = $about;

        return $this;
    }

    /**
     * @param \DateTime $birthDate
     * @return User
     */
    public function setBirthDate(\DateTimeInterface $birthDate): User
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBirthDate(): ?string
    {
        return $this->birthDate !== null ? $this->birthDate->format('Y-m-d') : null;
    }

    /**
     * @param mixed $roles
     * @return User
     */
    public function setRoles(array $roles): User
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt !== null ? $this->createdAt->format('Y-m-d') : null;
    }
}

