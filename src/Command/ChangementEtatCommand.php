<?php

namespace App\Command;


use App\Service\AffichageSortie;
use App\Service\ArchivageSortie;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ChangementEtatCommand extends Command
{
    protected static $defaultName = 'app:changement-etat';
    private $as;

    public function __construct(ArchivageSortie $as)
    {
        $this->as = $as;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Changement d\'état des sorties ');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->as->archiverSorties();
        return 0;
    }
}
