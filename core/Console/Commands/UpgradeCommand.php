<?php

namespace Vorkfork\Core\Console\Commands;

use Vorkfork\Application\ApplicationUtilities;
use Vorkfork\Database\Database;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:upgrade')]
class UpgradeCommand extends Command
{
    protected string $name = '';
    protected string $path;
    protected string $className = '';
    protected ApplicationUtilities $utilities;

    public function __construct(string $name = '')
    {
        $this->name = $name;
        parent::__construct();
        $this->utilities = ApplicationUtilities::getInstance();
    }

    public function run(InputInterface $input, OutputInterface $output): int
    {
        dump($this->utilities->getVersion());
        return self::SUCCESS;
    }

}