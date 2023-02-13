<?php

namespace Celeus\Config;

interface IConfig
{
    public function getConfig() : array;

    public function getConfigValue($key) : mixed;
}