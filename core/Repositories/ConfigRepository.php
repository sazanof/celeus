<?php

namespace Celeus\Core\Repositories;
/**
 * @method static ConfigRepository repository()
 */
class ConfigRepository extends CeleusRepository
{
    /**
     * @return mixed|object|null
     */
    public function getTimezone(): mixed
    {
        return $this->findOneBy(['app' => 'core', 'key' => 'timezone']);
    }
}