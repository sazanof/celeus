<?php
declare(strict_types=1);

namespace Vorkfork\Core\Models;

use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Vorkfork\Constraints\PasswordEquals;
use Vorkfork\Constraints\PasswordIsDifficult;
use Vorkfork\Core\Repositories\UserRepository;
use Vorkfork\Database\Entity;
use Vorkfork\Database\IdGenerator;
use Vorkfork\Database\Trait\Timestamps;
use Vorkfork\Security\PasswordHasher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Index(columns: ['id'], name: 'user_id')]
#[ORM\Index(columns: ['email'], name: 'email')]
#[ORM\Table(name: '`users`')]
#[ORM\HasLifecycleCallbacks]
class User extends Entity
{
	use Timestamps;

	#[ORM\Id]
	#[ORM\Column(type: Types::INTEGER, columnDefinition: "INT AUTO_INCREMENT NOT NULL UNIQUE")]
	#[ORM\GeneratedValue(strategy: 'CUSTOM')]
	#[ORM\CustomIdGenerator(class: IdGenerator::class)]
	private int|null $id = null;

	#[ORM\Column(type: Types::STRING, unique: true)]
	private string $username;

	#[ORM\Column(type: Types::STRING)]
	#[Ignore]
	private string $password;

	#[ORM\Column(type: Types::STRING, unique: true)]
	private string $email;

	#[ORM\Column(type: Types::STRING)]
	private string $firstname;

	#[ORM\Column(type: Types::STRING)]
	private string $lastname;

	#[ORM\Column(type: Types::STRING, nullable: true)]
	private string $photo;

	#[ORM\Column(type: Types::STRING, nullable: true, columnDefinition: "VARCHAR(255) AFTER `photo`")]
	private string $language;

	#[ORM\Column(type: Types::STRING, nullable: true, columnDefinition: "VARCHAR(255) AFTER `language`")]
	private string $organization;

	#[ORM\Column(type: Types::STRING, nullable: true, columnDefinition: "VARCHAR(255) AFTER `organization`")]
	private string $position;

	#[ORM\Column(type: Types::STRING, nullable: true, columnDefinition: "VARCHAR(35) AFTER `position`")]
	private ?string $phone;

	#[ORM\Column(type: Types::TEXT, columnDefinition: "TEXT AFTER `phone`")]
	private string $about;

	#[ORM\JoinTable(name: 'users_groups')]
	#[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
	#[ORM\InverseJoinColumn(name: 'group_id', referencedColumnName: 'id')]
	#[ORM\ManyToMany(targetEntity: Group::class)]
	#[ORM\OrderBy(["name" => "ASC"])]
	private Collection|ArrayCollection $groups;

	protected array $fillable = [
		'username',
		'email',
		'password',
		'firstname',
		'lastname',
		'language',
		'organization',
		'position',
		'phone',
		'about',
		'groups',
		'permissions'
	];

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getUsername(): string
	{
		return $this->username;
	}

	/**
	 * @param string $username
	 */
	public function setUsername(string $username): void
	{
		$this->username = $username;
	}


	/**
	 * @param string $password
	 */
	public function setPassword(string $password): void
	{
		$this->password = PasswordHasher::hash($password);
	}

	/**
	 * @return string
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail(string $email): void
	{
		$this->email = $email;
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

	public function getPhoto()
	{
		return $this->photo;
	}

	public function setPhoto(string $photo)
	{
		$this->photo = $photo;
	}

	/**
	 * @return string
	 */
	public function getOrganization(): string
	{
		return $this->organization;
	}

	/**
	 * @param string $organization
	 */
	public function setOrganization(string $organization): void
	{
		$this->organization = $organization;
	}

	/**
	 * @return string
	 */
	public function getPosition(): string
	{
		return $this->position;
	}

	/**
	 * @param string $position
	 */
	public function setPosition(string $position): void
	{
		$this->position = $position;
	}

	/**
	 * @return ?string
	 */
	public function getPhone(): ?string
	{
		return $this->phone;
	}

	/**
	 * @param ?string $phone
	 */
	public function setPhone(?string $phone): void
	{
		$this->phone = $phone;
	}

	/**
	 * @return string
	 */
	public function getAbout(): string
	{
		return $this->about;
	}

	/**
	 * @param string $about
	 */
	public function setAbout(string $about): void
	{
		$this->about = $about;
	}

	/**
	 * @return string
	 */
	public function getLanguage(): string
	{
		return $this->language;
	}

	/**
	 * @param string $language
	 */
	public function setLanguage(string $language): void
	{
		$this->language = $language;
	}

	/**
	 * @param array $groups
	 */
	public function setGroups(array $groups): void
	{
		$this->groups = new ArrayCollection($groups);
	}

	public function clearGroups(): void
	{
		$this->groups = new ArrayCollection([]);
	}

	/**
	 * @return ArrayCollection|Collection
	 */
	public function getGroups(): ArrayCollection|Collection
	{
		return $this->groups;
	}

	/**
	 * @throws \Vorkfork\Core\Exceptions\EntityAlreadyExistsException
	 */
	#[ORM\PrePersist]
	public function checkUserOnDuplicate(PrePersistEventArgs $args)
	{
		$this->validate();
		$this->checkExistingRecords(['email' => $this->email, 'username' => $this->username], $args);
	}

	public static function repository(): UserRepository|ObjectRepository
	{
		return parent::repository();
	}

	public static function loadValidatorMetadata(ClassMetadata $metadata)
	{
		$metadata->addPropertyConstraint('username', new NotBlank());
		//$metadata->addPropertyConstraint('username', new Regex('[a-z0-9]+'));
		$metadata->addPropertyConstraint('email', new NotBlank());
		$metadata->addPropertyConstraint('email', new Email());
		$metadata->addPropertyConstraint('firstname', new NotBlank());
		$metadata->addPropertyConstraint('firstname', new Length(['min' => 3]));
		$metadata->addPropertyConstraint('lastname', new NotBlank());
		$metadata->addPropertyConstraint('lastname', new Length(['min' => 3]));
		$metadata->addPropertyConstraint('password', new NotBlank());
		$metadata->addPropertyConstraint('password', new Length(['min' => 8]));
		$metadata->addPropertyConstraint('password', new Length(['min' => 8]));
		$metadata->addPropertyConstraint('password', new PasswordEquals());
		$metadata->addPropertyConstraint('password', new PasswordIsDifficult());
	}

}