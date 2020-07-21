<?php

namespace App\Command;

use App\Service\ArchivageSortie;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class EnCoursCommand extends Command
{
    protected static $defaultName = 'app:en-cours';

    private $as;

    public function __construct(ArchivageSortie $as)
    {
        $this->as = $as;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Passage des Sortie à En cours , et à clôturée la date de fin d\'inscriptions est dépassée');

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
      $bob= $this->as->clotureEtEnCoursSorties();

        $io->success($bob[0].' '.$bob[1].' '.$bob[2].' '.$bob[3]);

        return 0;
    }
}
