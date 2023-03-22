<?php
declare(strict_types=1);

namespace Vorkfork\Core\Models;

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

	#[ORM\Column(type: Types::STRING)]
	private string $photo;

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
	public function checkUserOnDuplicate(LifecycleEventArgs $args)
	{
		$this->checkExistingRecords(['email' => $this->email, 'username' => $this->username], $args);
	}

	public static function repository(): UserRepository|ObjectRepository
	{
		return parent::repository();
	}

}