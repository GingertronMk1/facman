<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240722125351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable('addresses');
        $table->addColumn('addressee_type', 'string');
        $table->addColumn('addressee_id', 'string');
        $table->addColumn('address_type', 'string');
        $table->addColumn('line1', 'string', ['notnull' => false]);
        $table->addColumn('line2', 'string', ['notnull' => false]);
        $table->addColumn('line3', 'string', ['notnull' => false]);
        $table->addColumn('postcode', 'string', ['notnull' => false]);
        $table->addColumn('city', 'string', ['notnull' => false]);
        $table->addColumn('country', 'string', ['notnull' => false]);
        $table->addColumn('created_at', 'string');
        $table->addColumn('updated_at', 'string');
        $table->addColumn('deleted_at', 'string', ['notnull' => false]);
        $table->setPrimaryKey(['addressee_type', 'addressee_id', 'address_type']);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable('addresses');
    }
}
