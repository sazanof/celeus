<?php

namespace Celeus\Template;

interface ITemplateRenderer
{
    public function loadTemplate(string $name): string;
}
