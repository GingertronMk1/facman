<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240719211501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $sitesTable = $schema->createTable('sites');
        $sitesTable->addColumn('id', 'string');
        $sitesTable->addColumn('name', 'string');
        $sitesTable->addColumn('description', 'text', ['notnull' => false]);
        $sitesTable->addColumn('created_at', 'datetime');
        $sitesTable->addColumn('updated_at', 'datetime');
        $sitesTable->addColumn('deleted_at', 'datetime', ['notnull' => false]);
        $sitesTable->setPrimaryKey(['id']);

        $buildingsTable = $schema->createTable('buildings');
        $buildingsTable->addColumn('id', 'string');
        $buildingsTable->addColumn('site_id', 'string');
        $buildingsTable->addColumn('name', 'string');
        $buildingsTable->addColumn('description', 'text', ['notnull' => false]);
        $buildingsTable->addColumn('created_at', 'datetime');
        $buildingsTable->addColumn('updated_at', 'datetime');
        $buildingsTable->addColumn('deleted_at', 'datetime', ['notnull' => false]);
        $buildingsTable->setPrimaryKey(['id']);
        $buildingsTable->addForeignKeyConstraint($sitesTable, ['site_id'], ['id']);

        $floorsTable = $schema->createTable('floors');
        $floorsTable->addColumn('id', 'string');
        $floorsTable->addColumn('building_id', 'string');
        $floorsTable->addColumn('name', 'string');
        $floorsTable->addColumn('description', 'text', ['notnull' => false]);
        $floorsTable->addColumn('created_at', 'datetime');
        $floorsTable->addColumn('updated_at', 'datetime');
        $floorsTable->addColumn('deleted_at', 'datetime', ['notnull' => false]);
        $floorsTable->setPrimaryKey(['id']);
        $floorsTable->addForeignKeyConstraint($buildingsTable, ['building_id'], ['id']);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
