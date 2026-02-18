<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260218001906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE perfumes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, perfume_url VARCHAR(255) NOT NULL, category VARCHAR(100) NOT NULL, stock INT NOT NULL, image_url VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, store_id INT NOT NULL, INDEX IDX_8D0CB425B092A811 (store_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE stores (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, base_url VARCHAR(100) NOT NULL, logo VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE perfumes ADD CONSTRAINT FK_8D0CB425B092A811 FOREIGN KEY (store_id) REFERENCES stores (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE perfumes DROP FOREIGN KEY FK_8D0CB425B092A811');
        $this->addSql('DROP TABLE perfumes');
        $this->addSql('DROP TABLE stores');
    }
}
