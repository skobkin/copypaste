<?php

namespace App\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;
use App\Entity\Language;

class LoadLanguages extends Fixture
{
    /** @var string */
    private $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    public function load(ObjectManager $manager)
    {
        $geshiPath =  $this->projectDir.'/vendor/theodordiaconu/geshi/src/GeSHi/geshi';

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

        /* @var $file \SplFileInfo */
        foreach ($finder as $file) {
            include $geshiPath.DIRECTORY_SEPARATOR.$file->getRelativePathname();

            $language = new Language(
                $language_data['LANG_NAME'],
                basename($file->getRelativePathname(), '.php')
            );

            $manager->persist($language);
        }

        $manager->flush();
    }
}
