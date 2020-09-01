<?php

declare(strict_types=1);

namespace Doctrine\Migrations;

abstract class AbstractMigration
{
    // Fake class
}

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class CustomMigrationWithDescription extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add new table';
    }

    public function up(): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }

    public function down(): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }
}

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class CustomMigrationWithoutDescription extends AbstractMigration
{
    public function up(): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }

    public function down(): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }
}

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class CustomMigrationWithEmptyDescription extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }

    public function down(): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }
}
