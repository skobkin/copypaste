<?php

namespace App\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Query;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use App\Entity\Language;

/**
 * Description of LoadLanguages
 *
 * @author Alexey Skobkin
 */
class LoadLanguages implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    
    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $output = new ConsoleOutput();
        
        $geshiPath = $this->container->get('kernel')->getRootDir() . '/../vendor/theodordiaconu/geshi/src/GeSHi/geshi';
        
        $finder = new Finder();
        $finder->files()->in($geshiPath);
        
        // Fix constants
        define('GESHI_CAPS_NO_CHANGE', true);
        define('GESHI_COMMENTS', true);
        define('GESHI_NEVER', true);
        define('GESHI_SEARCH', true);
        define('GESHI_REPLACE', true);
        define('GESHI_MODIFIERS', true);
        define('GESHI_BEFORE', true);
        define('GESHI_AFTER', true);
        define('GESHI_MAYBE', true);
        define('GESHI_NUMBER_INT_BASIC', true);
        define('GESHI_NUMBER_HEX_PREFIX_DOLLAR', true);
        define('GESHI_NUMBER_BIN_PREFIX_PERCENT', true);
        define('GESHI_NUMBER_FLT_NONSCI', true);
        define('GESHI_NUMBER_HEX_PREFIX', true);
        define('GESHI_NUMBER_FLT_SCI_ZERO', true);
        define('GESHI_NUMBER_OCT_PREFIX', true);
        define('GESHI_ALWAYS', true);
        define('GESHI_CAPS_UPPER', true);
        define('GESHI_NUMBER_FLT_NONSCI_F', true);
        define('GESHI_NUMBER_FLT_SCI_SHORT', true);
        define('GESHI_NUMBER_HEX_SUFFIX', true);
        define('GESHI_NUMBER_INT_CSTYLE', true);
        define('GESHI_NUMBER_BIN_PREFIX_0B', true);
        define('GESHI_NUMBER_BIN_SUFFIX', true);
        define('GESHI_NUMBER_OCT_SUFFIX', true);
        define('GESHI_CLASS', true);
        define('GESHI_NUMBER_OCT_PREFIX_0O', true);
        define('GESHI_NUMBER_OCT_PREFIX_AT', true);
        
        /* @var $file SplFileInfo */
        foreach ($finder as $file) {
            $output->writeln($file->getRelativePathname() . ' found. Parsing...');
            
            include $geshiPath.DIRECTORY_SEPARATOR.$file->getRelativePathname();
            
            $language = new Language();
            $language
                ->setName($language_data['LANG_NAME'])
                ->setCode(basename($file->getRelativePathname(), '.php'))
                ->setIsEnabled(true)
            ;
            
            $output->write('---> "' . $language->getName() . '"');
            $manager->persist($language);
            $manager->flush();
            $output->writeln('   [ PERSISTED ]');
        }
        
        $output->writeln('Import finished!');
    }
}
