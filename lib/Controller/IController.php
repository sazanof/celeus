<?php

namespace Celeus\Controller;

interface IController
{
    public function execute($className, $method, $params = []);
}
