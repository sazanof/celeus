<?php

namespace Vorkfork\Template;

use Symfony\Component\HttpFoundation\Response;

interface ITemplateRenderer
{
    public function loadTemplate(string $name): string|Response;
}
