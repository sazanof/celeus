<?php

namespace Vorkfork\Controller;

interface IController
{
    public function execute($className, $method, $params = []);
}
