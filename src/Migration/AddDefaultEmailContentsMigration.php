<?php

namespace Craffft\AccountmailBundle\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Craffft\AccountmailBundle\Util\Updater;

class AddDefaultEmailContentsMigration extends AbstractMigration
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
        return $this->updater->canAddDefaultEmailContents();
    }

    public function run(): MigrationResult
    {
        $this->updater->addDefaultEmailContents();

        return $this->createResult(
            true,
            'Added default email contents, if missing.'
        );
    }
}
