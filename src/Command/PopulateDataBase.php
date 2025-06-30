<?php

namespace App\Command;

use Doctrine\Bundle\FixturesBundle\Loader\SymfonyFixturesLoader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Application;

#[AsCommand(name: 'populatedb')] // run this with command [php bin/console populatedb] -> add alias line in services.yaml for autowiring
// we create a custom command that ensures that our fixtures groups are called in correct order without linking them
class PopulateDataBase extends Command
{
    // Fixture groups must be loaded in this predefined order
    private array $groups = ['userRelated', 'festivalRelated', 'artistAmenity', 'purchaseRelated'];

    public function __construct(
        private readonly ManagerRegistry $managerRegistry,
        private readonly SymfonyFixturesLoader $fixturesLoader,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Drops and updates the schema, then loads fixture groups in a defined order.'
                                    .'\n WARNING: All existing data will be lost!');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Step 1: Drop the schema forcefully (we do this that entries start from PK id = 1 not from old entries count)
        $io->section('Step 1: Reset FK starting increment point');
        $this->runCommand($output, 'doctrine:schema:drop', ['--force' => true]);

        // Step 2: Update schema based on current entities
        $io->section('Step 2: Rerun SQL queries to create tables');
        $this->runCommand($output, 'doctrine:schema:update', ['--force' => true]);

        // Step 3: Load fixtures group by group
        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->managerRegistry->getManager(), $purger);

        foreach ($this->groups as $group) {
            $io->section("Loading fixture group: $group");

            // Correct usage of getFixtures with 'group' option
            $fixtures = $this->fixturesLoader->getFixtures(['group' => $group]);
            if (empty($fixtures)) {
                $io->warning("No fixtures found for group '$group'");
                continue;
            }

            $executor->execute($fixtures, true); //we need to APPEND to keep data entries from previous executed fixture groups
        }

        $io->success('Database populated SUCCESSFULLY!');
        return Command::SUCCESS;
    }

    /**
     * Helper method to run internal Symfony commands like doctrine:schema:drop
     */
    private function runCommand(OutputInterface $output, string $commandName, array $arguments = []): void
    {
        $application = $this->getApplication();
        $command = $application->find($commandName);
        $input = new ArrayInput($arguments);
        $input->setInteractive(false);
        $command->run($input, $output);
    }
}
