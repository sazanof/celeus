<?php

namespace Vorkfork\Template;

interface ITemplateRenderer
{
    public function loadTemplate(string $name): string;
}
