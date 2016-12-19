<?php

namespace Skobkin\Bundle\CopyPasteBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;

/**
 * Deleting expired copypastes by Cron
 *
 * @author Alexey Skobkin
 */
class DropExpiredCopypastesCommand extends ContainerAwareCommand
{
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
        
        /* @var $em EntityManager */
        $em = $this->getContainer()->get('doctrine')->getManager();

        // @todo move to repository
        $queryBuilder = $em->createQueryBuilder()
            ->delete('SkobkinCopyPasteBundle:Copypaste c')
            ->where('c.dateExpire < :now')
            ->andWhere('c.dateExpire IS NOT NULL')
            ->setParameter('now', new \DateTime());
        $queryBuilder->getQuery()->execute();

        $output->writeln('Done.');
    }
}
