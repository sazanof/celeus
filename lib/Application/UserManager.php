<?php

namespace Vorkfork\Application;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\TransactionRequiredException;
use Sabre\DAV\Exception\NotFound;
use Sabre\DAV\FS\Directory;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validation;
use Vorkfork\Apps\Dav\Acl\ACLDirectory;
use Vorkfork\Core\DAV\DavConfigurator;
use Vorkfork\Core\Models\User;
use Vorkfork\Database\Entity;
use Vorkfork\DTO\UserDto;
use Vorkfork\File\Avatar;
use Vorkfork\Graphics\Image;

class UserManager
{
	protected Avatar $avatar;
	protected User|Entity $model;
	protected DavConfigurator $configurator;
	protected Directory $directory;
	protected string $principalRootDirectory;
	protected string $owner;
	protected static ?UserManager $instance = null;

	/**
	 * @throws OptimisticLockException
	 * @throws TransactionRequiredException
	 * @throws ORMException
	 */
	public function __construct(int $id)
	{
		$this->model = User::find($id);
		$this->configurator = DavConfigurator::getInstance();
		$this->setOwner();
		$this->principalRootDirectory = $this->makeUserPrincipalSharesUri();
		$this->directory = new ACLDirectory($this->principalRootDirectory, $this->getOwner());
		self::$instance = $this;
	}

	/**
	 * @throws OptimisticLockException
	 * @throws TransactionRequiredException
	 * @throws ORMException
	 */
	public static function getManagerStatic(int $id): UserManager
	{
		if (self::$instance instanceof UserManager && $id === self::$instance->model->getId()) {
			return self::$instance;
		}
		return new self($id);
	}

	public function hasAvatar()
	{
		return $this->directory->childExists(Avatar::DIRECTORY . DIRECTORY_SEPARATOR . $this->model->getPhoto());
	}

	public function avatarPath(bool $absolute = false)
	{
		return $absolute
			? $this->getPrincipalRootDirectory() . DIRECTORY_SEPARATOR . Avatar::DIRECTORY . DIRECTORY_SEPARATOR . $this->model->getPhoto()
			: Avatar::DIRECTORY . DIRECTORY_SEPARATOR . $this->model->getPhoto();
	}

	/**
	 * @throws NotFound
	 */
	public function getAvatar()
	{
		return $this->directory->getChild(Avatar::DIRECTORY . DIRECTORY_SEPARATOR . $this->model->getPhoto());
	}

	protected function makeUserPrincipalSharesUri(): string
	{
		return Path::normalize($this->configurator->getSharesPath() .
			DIRECTORY_SEPARATOR .
			$this->model->getUsername());
	}

	/**
	 * @return Directory
	 */
	public function getDirectory(): Directory
	{
		return $this->directory;
	}

	/**
	 * @param Request $request
	 * @return \Vorkfork\DTO\BaseDto|UserDto
	 * @throws NotFound
	 * @throws ORMException
	 * @throws OptimisticLockException
	 * @throws \Doctrine\ORM\Exception\MissingMappingDriverImplementation
	 */
	public function setAvatar(Request $request): \Vorkfork\DTO\BaseDto|UserDto
	{
		/** @var UploadedFile $file * */
		$file = $request->files->get('file');
		$coordinates = $request->get('coordinates');
		$validator = Validation::createValidator();
		$violations = $validator->validate($file, new File(
			[
				'maxSize' => '1024k',
				'mimeTypes' => [
					'image/jpeg',
					'image/png',
				],
				'mimeTypesMessage' => 'Please upload jpeg or png files',
			]
		));
		if ($violations->count() > 0) {
			throw new ValidationFailedException('Error on saving photo', $violations);
		}

		if (!$this->directory->childExists(Avatar::DIRECTORY)) {
			$this->directory->createDirectory(Avatar::DIRECTORY);
		}
		$filename = $this->model->getUsername() . '.' . $file->guessExtension();
		$f = Image::fromData($file->getContent())
			->crop($coordinates['left'], $coordinates['top'], $coordinates['width'], $coordinates['height'])
			->get();

		$avatarCollection = $this->directory->getChild(Avatar::DIRECTORY)->getChildren();
		if (!empty($avatarCollection)) {
			foreach ($avatarCollection as $file) {
				$file->delete();
			}
		}

		$this->directory->getChild(Avatar::DIRECTORY)
			->createFile($filename, $f);
		$this->model->setPhoto($filename);
		$this->model->save();

		return $this->model->toDto(UserDto::class);
	}

	/**
	 * @return string
	 */
	public function getOwner(): string
	{
		return $this->owner;
	}

	/**
	 * @return string
	 */
	public function getPrincipalRootDirectory(): string
	{
		return $this->principalRootDirectory;
	}

	public function getPrincipalAvatarsRootDirectory(): string
	{
		return $this->principalRootDirectory . DIRECTORY_SEPARATOR . Avatar::DIRECTORY;
	}

	/**
	 * @return void
	 */
	public function setOwner(): void
	{
		$this->owner = $this->configurator->getBaseUri() . DIRECTORY_SEPARATOR . $this->model->getUsername();
	}
}