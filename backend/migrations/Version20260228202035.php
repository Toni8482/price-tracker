<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260228202035 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE precio_contenido (id INT AUTO_INCREMENT NOT NULL, precio DOUBLE PRECISION NOT NULL, contenido VARCHAR(255) NOT NULL, perfume_id INT NOT NULL, INDEX IDX_2E2600D6AA91F2AA (perfume_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE precio_contenido ADD CONSTRAINT FK_2E2600D6AA91F2AA FOREIGN KEY (perfume_id) REFERENCES perfumes (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE precio_contenido DROP FOREIGN KEY FK_2E2600D6AA91F2AA');
        $this->addSql('DROP TABLE precio_contenido');
    }
}
