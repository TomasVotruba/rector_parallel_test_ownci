<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210530064733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        if ($this->connection->getDatabasePlatform()->getName() == 'mysql') {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE `repeat` CHANGE prototyp_id prototyp_id INT DEFAULT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        if ($this->connection->getDatabasePlatform()->getName() == 'mysql') {
            // this down() migration is auto-generated, please modify it to your needs
            $this->addSql('ALTER TABLE `repeat` CHANGE prototyp_id prototyp_id INT NOT NULL');
        }
    }
}