<?php

namespace Craffft\AccountmailBundle\ContaoManager;

use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Craffft\TranslationFieldsBundle\CraffftTranslationFieldsBundle;

class Plugin
{
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(CraffftTranslationFieldsBundle::class),
        ];
    }
}
