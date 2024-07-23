<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240723111911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add job statuses';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable('job_statuses');
        $table->addColumn('id', 'string');
        $table->addColumn('name', 'string');
        $table->addColumn('colour', 'string');
        $table->addColumn('description', 'string', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable('job_statuses');
    }
}
