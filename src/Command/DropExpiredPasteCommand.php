<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Paste;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Deleting expired copypastes by Cron
 *
 * @author Alexey Skobkin
 */
class DropExpiredPasteCommand extends Command
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setName('copypaste:cron:drop-expired')
            ->setDescription('Drop expired copypastes')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write('Deleting expired entities...');

        // @todo move to repository
        $qb = $this->em->createQueryBuilder()
            ->delete(Paste::class, 'c')
            ->where('c.dateExpire < :now')
            ->andWhere('c.dateExpire IS NOT NULL')
            ->setParameter('now', new \DateTime());
        $qb->getQuery()->execute();

        $output->writeln('Done.');
    }
}
