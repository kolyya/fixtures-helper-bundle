<?php

namespace Kolyya\FixturesHelperBundle\Command;

use Kolyya\FixturesHelperBundle\DependencyInjection\Configuration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FixturesLoadCommand extends Command
{
    protected static $defaultName = 'kolyya:fixtures:load';

    /**
     * @var array
     */
    private $loadConfig;

    public function __construct(array $loadConfig, string $name = null)
    {
        parent::__construct($name);

        $this->loadConfig = $loadConfig;
    }

    protected function configure()
    {
        $this
            ->setDescription('Deletes the database, creates the database, updates the schema, loads fixtures');
        $this->addOption('force', null, InputOption::VALUE_OPTIONAL, 'Execute without confirmation?', false);
        $this->addOption('config', null, InputOption::VALUE_OPTIONAL, 'Config name', 'default');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $configName =  $input->getOption('config');

        if ($input->getOption('env') !== 'test' && $input->getOption('force') === false) {
            $pass = rand(100, 999);

            $io->warning('This action will destroy the database and all entries in it!');
            $test = $io->ask(sprintf("To confirm, enter this code below: %d", $pass), 0);

            if ((int)$test !== $pass) {
                $io->text('The action is canceled.');
                return;
            }
        }

        $commandsArr = [
            ['name' => 'doctrine:database:drop', 'args' => $this->loadConfig[$configName]['drop']],
            ['name' => 'doctrine:database:create', 'args' => $this->loadConfig[$configName]['create']],
            ['name' => 'doctrine:schema:update', 'args' => $this->loadConfig[$configName]['update']],
            ['name' => 'doctrine:fixtures:load', 'args' => $this->loadConfig[$configName]['load']],
        ];

        foreach ($commandsArr as $item) {
            $command = $this->getApplication()->find($item['name']);

            $greetInput = new ArrayInput($item['args']);
            $command->run($greetInput, $output);
        }

        $io->success('New data uploaded to the database');

        return 0;
    }
}
