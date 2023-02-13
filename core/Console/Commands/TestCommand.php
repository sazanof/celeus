<?php

namespace Celeus\Core\Console\Commands;

use Celeus\Database\Database;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'test:run')]
class TestCommand extends Command
{
    protected string $name = '';
    protected string $path;
    protected string $className = '';

    public function __construct(string $name = '')
    {
        $this->name = $name;
        parent::__construct();
    }

    public function run(InputInterface $input, OutputInterface $output): int
    {
        echo 123;
    }

}