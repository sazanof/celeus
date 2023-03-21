<?php

namespace Vorkfork\Core\Repositories;

use Vorkfork\Core\Models\Config;

/**
 * @method static ConfigRepository repository()
 */
class ConfigRepository extends Repository
{
    /**
     * @return ?Config
     */
    public function getTimezone(): ?Config
    {
        return $this->findOneBy(['app' => 'core', 'key' => 'timezone']);
    }

    /**
     * @return Config|null
     */
    public function getLocale(): ?Config
    {
        return $this->findOneBy(['app' => 'core', 'key' => 'locale']);
    }
}