<?php

namespace Kolyya\FixturesHelperBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class FixturesLoadCommand extends DoctrineCommand
{
    /**
     * @var array
     */
    private $loadConfig;

    /**
     * @var null|string
     */
    private $backupPath;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(array $loadConfig, ?string $backupPath, ManagerRegistry $doctrine)
    {
        $this->loadConfig = $loadConfig;
        $this->backupPath = $backupPath;

        parent::__construct($doctrine);
    }

    protected function configure()
    {
        $this
            ->setName('kolyya:fixtures:load')
            ->setDescription('Deletes the database, creates the database, updates the schema, loads fixtures')
            ->addOption('em', null, InputOption::VALUE_REQUIRED, 'The entity manager to use for this command.', 'default')
            ->addOption('force', null, InputOption::VALUE_OPTIONAL, 'Execute without confirmation?', false)
            ->addOption('config', null, InputOption::VALUE_OPTIONAL, 'Config name', 'default');
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

        $this->em = $this->getDoctrine()->getManager($input->getOption('em'));

        if ($this->backupPath) {
            $backupFilename = $this->doBackup($io);

            if (false === $backupFilename) {
                $io->error('Backup file was not created');
                return 0;
            }
        }

        $configName = $input->getOption('config');

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
            ['name' => 'doctrine:schema:drop', 'args' => $this->loadConfig[$configName]['drop']],
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

    private function doBackup(SymfonyStyle $io)
    {
        $dbParams = $this->em->getConnection()->getParams();

        if ($dbParams['driver'] !== 'pdo_mysql') {
            $io->warning("DB Driver '{$dbParams['driver']}' not supported");
            return false;
        }

        $filename = $this->backupPath . '/' . date("YmdHis") . '.sql';

        $command = [
            'mysqldump',
            '-u' . $dbParams['user'],
            '-p' . $dbParams['password'],
            '-h' . $dbParams['host'],
            $dbParams['dbname'],
            '>"' . $filename . '"'
        ];

        $io->text('Run mysqldump command:');
        $io->block(implode(' ', $command), null, 'fg=yellow');
        $process = Process::fromShellCommandline(implode(' ', $command));
        $process->run(function ($type, $buffer) use ($io) {
            $io->note($buffer);
        }, []);

        if (!file_exists($filename)) {
            $io->warning("Backup file was not created");
            return false;
        }

        if (0 === filesize($filename)) {
            $io->warning("Backup file is empty");
            return false;
        }

        $io->success('Database backup should be here: ' . $filename);

        return $filename;
    }
}
