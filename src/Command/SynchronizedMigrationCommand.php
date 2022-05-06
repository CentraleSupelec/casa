<?php

namespace App\Command;

namespace App\Command;

use Exception as ExceptionAlias;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\LockInterface;

class SynchronizedMigrationCommand extends Command
{
    protected static $defaultName = 'app:sync-migrate';

    private LockInterface $lock;

    public function __construct(LockFactory $lockFactory, string $name = null)
    {
        parent::__construct($name);
        $this->lock = $lockFactory->createLock('database-migration');
    }

    protected function configure()
    {
        parent::configure();

        $this
            ->setDescription('Doctrine migration with lock')
            ->addOption('conn', null, InputOption::VALUE_REQUIRED, 'The database connection to use for this command.')
            ->addOption('em', null, InputOption::VALUE_REQUIRED, 'The entity manager to use for this command.')
            ->addOption('shard', null, InputOption::VALUE_REQUIRED, 'The shard connection to use for this command.');
    }

    /**
     * @throws ExceptionAlias
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $app = $this->getApplication();

        // Attempt to lock and block if the lock is unavailable

        $this->lock->acquire(true);

        $arguments = [
            'command' => 'doctrine:migrations:migrate',
        ];
        if ($input->getOption('em')) {
            $arguments['--em'] = $input->getOption('em');
        }
        if ($input->getOption('conn')) {
            $arguments['--conn'] = $input->getOption('conn');
        }
        if ($input->getOption('shard')) {
            $arguments['--shard'] = $input->getOption('shard');
        }
        $newInput = new ArrayInput(array_merge(
            $arguments
        ));
        $newInput->setInteractive(false);

        return $app->run($newInput, $output);
    }
}
