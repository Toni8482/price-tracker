<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260304215217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE favorites_perfumes (user_id INT NOT NULL, perfumes_id INT NOT NULL, INDEX IDX_3CBD8789A76ED395 (user_id), INDEX IDX_3CBD8789D1D86621 (perfumes_id), PRIMARY KEY (user_id, perfumes_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE favorites_perfumes ADD CONSTRAINT FK_3CBD8789A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorites_perfumes ADD CONSTRAINT FK_3CBD8789D1D86621 FOREIGN KEY (perfumes_id) REFERENCES perfumes (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorites_perfumes DROP FOREIGN KEY FK_3CBD8789A76ED395');
        $this->addSql('ALTER TABLE favorites_perfumes DROP FOREIGN KEY FK_3CBD8789D1D86621');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE favorites_perfumes');
    }
}
