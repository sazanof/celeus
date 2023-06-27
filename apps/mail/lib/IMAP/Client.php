<?php

namespace Vorkfork\Apps\Mail\IMAP;

use Webklex\PHPIMAP\Exceptions\AuthFailedException;
use Webklex\PHPIMAP\Exceptions\ConnectionFailedException;
use Webklex\PHPIMAP\Exceptions\FolderFetchingException;
use Webklex\PHPIMAP\Exceptions\ImapBadRequestException;
use Webklex\PHPIMAP\Exceptions\ImapServerErrorException;
use Webklex\PHPIMAP\Exceptions\ResponseException;
use Webklex\PHPIMAP\Exceptions\RuntimeException;
use Webklex\PHPIMAP\Folder;
use Webklex\PHPIMAP\Support\FolderCollection;

class Client extends \Webklex\PHPIMAP\Client
{
	/**
	 * Get folders list.
	 * If hierarchical order is set to true, it will make a tree of folders, otherwise it will return flat array.
	 *
	 * @param boolean $hierarchical
	 * @param string|null $parent_folder
	 * @param bool $soft_fail If true, it will return an empty collection instead of throwing an exception
	 *
	 * @return FolderCollection
	 * @throws AuthFailedException
	 * @throws ConnectionFailedException
	 * @throws FolderFetchingException
	 * @throws ImapBadRequestException
	 * @throws ImapServerErrorException
	 * @throws ResponseException
	 * @throws RuntimeException
	 */
	public function getFolders(bool $hierarchical = true, string $parent_folder = null, bool $soft_fail = false): FolderCollection
	{
		$this->checkConnection();
		$folders = FolderCollection::make([]);

		$pattern = $parent_folder . ($hierarchical ? '%' : '*');
		$items = $this->connection->folders('', $pattern)->validatedData();

		if (!empty($items)) {
			foreach ($items as $folder_name => $item) {
				$folder = new Folder($this, $folder_name, $item["delimiter"], $item["flags"]);

				if ($hierarchical && $folder->hasChildren()) {
					$pattern = $folder->full_name . $folder->delimiter . '%';

					$children = $this->getFolders(true, $pattern, $soft_fail);
					$folder->setChildren($children);
				}

				$folders->push($folder);
			}

			return $folders;
		} else if (!$soft_fail) {
			throw new FolderFetchingException("failed to fetch any folders");
		}

		return $folders;
	}

	/**
	 * Get folders list.
	 * If hierarchical order is set to true, it will make a tree of folders, otherwise it will return flat array.
	 *
	 * @param boolean $hierarchical
	 * @param string|null $parent_folder
	 * @param bool $soft_fail If true, it will return an empty collection instead of throwing an exception
	 *
	 * @return FolderCollection
	 * @throws FolderFetchingException
	 * @throws ConnectionFailedException
	 * @throws AuthFailedException
	 * @throws ImapBadRequestException
	 * @throws ImapServerErrorException
	 * @throws RuntimeException
	 * @throws ResponseException
	 */
	public function getFoldersWithStatus(bool $hierarchical = true, string $parent_folder = null, bool $soft_fail = false): FolderCollection
	{
		$this->checkConnection();
		$folders = FolderCollection::make([]);

		$pattern = $parent_folder . ($hierarchical ? '%' : '*');
		$items = $this->connection->folders('', $pattern)->validatedData();

		if (!empty($items)) {
			foreach ($items as $folder_name => $item) {
				$folder = new \Vorkfork\Apps\Mail\IMAP\Folder($this, $folder_name, $item["delimiter"], $item["flags"]);

				if ($hierarchical && $folder->hasChildren()) {
					$pattern = $folder->full_name . $folder->delimiter . '%';

					$children = $this->getFoldersWithStatus(true, $pattern, $soft_fail);
					$folder->setChildren($children);
				}

				$folder->loadStatus();
				$folders->push($folder);
			}

			return $folders;
		} else if (!$soft_fail) {
			throw new FolderFetchingException("failed to fetch any folders");
		}

		return $folders;
	}

}
