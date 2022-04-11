<?php

namespace App\Command;

use App\Entity\Equipment;
use App\Entity\HousingGroup;
use App\Entity\HousingGroupService;
use App\Entity\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

#[AsCommand(
    name: 'app:load-refdata',
    description: 'Loads reference data into the database',
)]
class LoadRefdataCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $defaultEntityManager)
    {
        $this->entityManager = $defaultEntityManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('filename', InputArgument::REQUIRED, 'name of the Yaml containing data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->warning('You are about to remove all services & equipements and all configuration related ( Housing group configuration )');

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Would you like to continue ? (y/N)', false);

        if (!$helper->ask($input, $output, $question)) {
            return Command::SUCCESS;
        }

        $entities = Yaml::parseFile($input->getArgument('filename'));

        $services = $entities['service'];
        $equipments = $entities['equipement'];

        if ($services) {
            $io->writeln('Load services');

            $this->removeAll(HousingGroupService::class);
            $this->removeAll(Service::class);
            $this->insertAll($io, $services, Service::class);
        }

        if ($equipments) {
            $io->writeln('Load equipments');

            $housingGroupRepository = $this->entityManager->getRepository(HousingGroup::class);

            $oldHousingGroups = $housingGroupRepository->findAll();
            foreach ($oldHousingGroups as $oldHousingGroup) {
                $oldEquipments = $oldHousingGroup->getEquipments();

                foreach ($oldEquipments as $equipment) {
                    $oldHousingGroup->removeEquipment($equipment);
                }
                $this->entityManager->persist($oldHousingGroup);
            }
            $this->entityManager->flush();

            $this->removeAll(Equipment::class);
            $this->insertAll($io, $equipments, Equipment::class);
        }

        $this->entityManager->flush();
        $io->success('Loading ended.');

        return Command::SUCCESS;
    }

    protected function removeAll($classType)
    {
        $references = $this->entityManager->getRepository($classType)->findAll();
        foreach ($references as $reference) {
            $this->entityManager->remove($reference);
        }
        $this->entityManager->flush();
    }

    protected function insertAll($io, $refTable, $classType)
    {
        $io->progressStart(count($refTable));

        foreach ($refTable as $newRef) {
            $obj = new $classType();
            $obj->setLabel($newRef['label']);
            $this->entityManager->persist($obj);
            $io->progressAdvance();
        }

        $io->progressFinish();
    }
}
