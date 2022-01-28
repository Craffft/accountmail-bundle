<?php

namespace Craffft\AccountmailBundle\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Craffft\AccountmailBundle\Util\Updater;

class InitTranslationFieldsMigration extends AbstractMigration
{
    /**
     * @var Updater
     */
    private $updater;

    public function __construct()
    {
        $this->updater = new Updater();
    }

    public function shouldRun(): bool
    {
        return $this->updater->canAddTranslationFieldTableAndFields();
    }

    public function run(): MigrationResult
    {
        $updater = new Updater();
        $updater->addTranslationFieldTableAndFields();

        return $this->createResult(
            true,
            'Added missing translation fields table.'
        );
    }
}
