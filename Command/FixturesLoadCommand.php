<?php

namespace Kolyya\FixturesHelperBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FixturesLoadCommand extends Command
{
    protected static $defaultName = 'kolyya:fixtures:load';

    protected function configure()
    {
        $this
            ->setDescription('Deletes the database, creates the database, updates the schema, loads fixtures');
        $this->addOption('force', null, InputOption::VALUE_OPTIONAL, 'fff', false);
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
            ['name' => 'doctrine:database:drop', 'args' => ['--force' => true]],
            ['name' => 'doctrine:database:create', 'args' => []],
            ['name' => 'doctrine:schema:update', 'args' => ['--force' => true]],
            ['name' => 'doctrine:fixtures:load', 'args' => ['--append' => true]],
        ];

        foreach ($commandsArr as $item) {
            $command = $this->getApplication()->find($item['name']);

            $greetInput = new ArrayInput($item['args']);
            $command->run($greetInput, $output);
        }

        $io->success('New data uploaded to the database');
    }
}
