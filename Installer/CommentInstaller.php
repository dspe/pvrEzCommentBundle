<?php

namespace pvr\EzCommentBundle\Installer;

use EzSystems\PlatformInstallerBundle\Installer\CleanInstaller;

class CommentInstaller extends CleanInstaller
{
    public function importSchema()
    {
        parent::importSchema();

        $this->runQueriesFromFile(
            __DIR__ . '/../Resources/installer/sql/schema.sql'
        );
    }
}