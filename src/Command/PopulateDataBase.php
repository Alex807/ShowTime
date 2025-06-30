<?php
namespace App\Command;

use Doctrine\Bundle\FixturesBundle\Loader\SymfonyFixturesLoader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'populate-database')] //run this with command [php bin/console populate-database]
//we create a custom command that ensures that our fixtures groups are called in correct order without linkage them
class PopulateDataBase extends Command
{
    private array $groups = ['userRelated', 'festivalRelated', 'artistAmenity', 'purchaseRelated'];

    public function __construct(
        private readonly ManagerRegistry $managerRegistry,
        private readonly SymfonyFixturesLoader $fixturesLoader
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Loads fixture groups in a defined order, all previous data will be lost!');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->managerRegistry->getManager(), $purger);

        foreach ($this->groups as $group) {
            $io->section("Loading fixture group: $group");

            $fixtures = $this->fixturesLoader->getFixtures(['group' => [$group]]);
            if (empty($fixtures)) {
                $io->warning("No fixtures found for group '$group'");
                continue;
            }

            $executor->execute($fixtures, true);
        }

        $io->success('Database populated SUCCESSFULLY !');
        return Command::SUCCESS;
    }
}
