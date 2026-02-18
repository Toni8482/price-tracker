<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260213000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create pc_component table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE pc_component (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(255) NOT NULL,
            price DOUBLE NOT NULL,
            url VARCHAR(500) NOT NULL UNIQUE,
            created_at DATETIME NOT NULL,
            category VARCHAR(100) NOT NULL,
            description LONGTEXT,
            stock INT,
            image_url VARCHAR(255),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE pc_component');
    }
}
