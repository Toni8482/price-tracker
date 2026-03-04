<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260226202045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE perfumes ADD CONSTRAINT FK_8D0CB4258100F9BD FOREIGN KEY (target_public_id) REFERENCES target_public (id)');
        $this->addSql('CREATE INDEX IDX_8D0CB4258100F9BD ON perfumes (target_public_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE perfumes DROP FOREIGN KEY FK_8D0CB4258100F9BD');
        $this->addSql('DROP INDEX IDX_8D0CB4258100F9BD ON perfumes');
    }
}
