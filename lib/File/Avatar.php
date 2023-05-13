<?php

namespace Vorkfork\File;

use LasseRafn\InitialAvatarGenerator\InitialAvatar;
use Symfony\Component\HttpFoundation\Response;
use Vorkfork\Apps\Dav\Acl\ACLFile;
use Vorkfork\Graphics\Image;

class Avatar extends ACLFile
{

	public const DIRECTORY = '.avatars';

	/**
	 * @param string $data
	 * @return string|void|null
	 */
	public function put($data)
	{
		file_put_contents($this->path, $data);
		clearstatcache(true, $this->path);
	}

	public function find(string $owner)
	{
		return $owner;
	}

	/**
	 * Get default User's avatar from initials
	 * @return Response
	 */
	public static function getDefault(string $name, int $size)
	{
		$avatar = new InitialAvatar();
		$size = $size > 512 ? 512 : ceil($size / 16) * 16;
		$image = $avatar->name($name)->size($size)->generate();
		$stream = $image->stream('png');
		return (new Response($stream->getContents(), 200, [
			'Content-Type' => $image->mime
		],));
	}

	public static function responseFromFile(ACLFile|string $file)
	{
		$resource = $file->get();
		if (is_resource($resource)) {
			$handle = $resource;
			$contents = '';
			while (!feof($handle)) {
				$contents .= fread($handle, 8096);
			}
			fclose($handle);
			return (new Response($contents, 200, [
				'Content-Type' => $file->getContentType()
			]));
		}
	}

	public static function responseFromImage(Image $image): Response
	{
		return (new Response($image->get(), 200, [
			'Content-Type' => $image->guessType()
		]));

	}

}