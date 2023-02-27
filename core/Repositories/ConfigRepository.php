<?php

namespace Vorkfork\Core\Repositories;
/**
 * @method static ConfigRepository repository()
 */
class ConfigRepository extends Repository
{
    /**
     * @return mixed|object|null
     */
    public function getTimezone(): mixed
    {
        return $this->findOneBy(['app' => 'core', 'key' => 'timezone']);
    }
}