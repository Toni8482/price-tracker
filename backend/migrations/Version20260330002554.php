<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260330002554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorites_perfum (user_id INT NOT NULL, precio_contenido_id INT NOT NULL, INDEX IDX_DAB2578A76ED395 (user_id), INDEX IDX_DAB25783D480961 (precio_contenido_id), PRIMARY KEY (user_id, precio_contenido_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE favorites_perfum ADD CONSTRAINT FK_DAB2578A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorites_perfum ADD CONSTRAINT FK_DAB25783D480961 FOREIGN KEY (precio_contenido_id) REFERENCES precio_contenido (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorites_perfum DROP FOREIGN KEY FK_DAB2578A76ED395');
        $this->addSql('ALTER TABLE favorites_perfum DROP FOREIGN KEY FK_DAB25783D480961');
        $this->addSql('DROP TABLE favorites_perfum');
    }
}
