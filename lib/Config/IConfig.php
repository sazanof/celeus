<?php

namespace Vorkfork\Config;

interface IConfig
{
    public function getConfig() : array;

    public function getConfigValue($key) : mixed;
}