<?php

declare(strict_types=1);

namespace Craffft\AccountmailBundle\ContaoManager;

use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Craffft\AccountmailBundle\CraffftAccountmailBundle;
use Craffft\TranslationFieldsBundle\CraffftTranslationFieldsBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(CraffftAccountmailBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class, CraffftTranslationFieldsBundle::class])
                ->setReplace(['accountmail']),
        ];
    }
}
