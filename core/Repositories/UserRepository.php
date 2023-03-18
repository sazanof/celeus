<?php
declare(strict_types=1);

namespace Vorkfork\Core\Repositories;

use Vorkfork\Core\Models\User;

/**
 * @method static UserRepository repository()
 */
class UserRepository extends Repository
{
    /**
     * @param $username
     * @return $this
     */
    public function whereUsername($username): static
    {
        $this->_qb
            ->where('c.username = :username')
            ->setParameter('username', $username);
        return $this;
    }

    /**
     * Get user by username
     * @param int $id
     * @return User
     */
    public function findById(int $id): User
    {
        return $this->find($id);
    }

    /**
     * Get user by username
     * @param $username
     * @return User
     */
    public function findByUsername($username)
    {
        return $this->select()->whereUsername($username)->_qb->getQuery()->getResult()[0];
    }
}