<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260608061613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE perfumes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, perfume_url VARCHAR(255) NOT NULL, category VARCHAR(100) NOT NULL, image_url VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, brand VARCHAR(100) NOT NULL, concentracion VARCHAR(100) NOT NULL, store_id INT NOT NULL, target_public_id INT NOT NULL, INDEX IDX_8D0CB425B092A811 (store_id), INDEX IDX_8D0CB4258100F9BD (target_public_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE precio_contenido (id INT AUTO_INCREMENT NOT NULL, precio DOUBLE PRECISION NOT NULL, contenido VARCHAR(255) NOT NULL, image_url VARCHAR(255) NOT NULL, perfume_id INT NOT NULL, INDEX IDX_2E2600D6AA91F2AA (perfume_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE stores (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, base_url VARCHAR(100) NOT NULL, logo VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE target_public (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE favorites_perfumes (user_id INT NOT NULL, perfumes_id INT NOT NULL, INDEX IDX_3CBD8789A76ED395 (user_id), INDEX IDX_3CBD8789D1D86621 (perfumes_id), PRIMARY KEY (user_id, perfumes_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE favorites_perfum (user_id INT NOT NULL, precio_contenido_id INT NOT NULL, INDEX IDX_DAB2578A76ED395 (user_id), INDEX IDX_DAB25783D480961 (precio_contenido_id), PRIMARY KEY (user_id, precio_contenido_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE perfumes ADD CONSTRAINT FK_8D0CB425B092A811 FOREIGN KEY (store_id) REFERENCES stores (id)');
        $this->addSql('ALTER TABLE perfumes ADD CONSTRAINT FK_8D0CB4258100F9BD FOREIGN KEY (target_public_id) REFERENCES target_public (id)');
        $this->addSql('ALTER TABLE precio_contenido ADD CONSTRAINT FK_2E2600D6AA91F2AA FOREIGN KEY (perfume_id) REFERENCES perfumes (id)');
        $this->addSql('ALTER TABLE favorites_perfumes ADD CONSTRAINT FK_3CBD8789A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorites_perfumes ADD CONSTRAINT FK_3CBD8789D1D86621 FOREIGN KEY (perfumes_id) REFERENCES perfumes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorites_perfum ADD CONSTRAINT FK_DAB2578A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorites_perfum ADD CONSTRAINT FK_DAB25783D480961 FOREIGN KEY (precio_contenido_id) REFERENCES precio_contenido (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE perfumes DROP FOREIGN KEY FK_8D0CB425B092A811');
        $this->addSql('ALTER TABLE perfumes DROP FOREIGN KEY FK_8D0CB4258100F9BD');
        $this->addSql('ALTER TABLE precio_contenido DROP FOREIGN KEY FK_2E2600D6AA91F2AA');
        $this->addSql('ALTER TABLE favorites_perfumes DROP FOREIGN KEY FK_3CBD8789A76ED395');
        $this->addSql('ALTER TABLE favorites_perfumes DROP FOREIGN KEY FK_3CBD8789D1D86621');
        $this->addSql('ALTER TABLE favorites_perfum DROP FOREIGN KEY FK_DAB2578A76ED395');
        $this->addSql('ALTER TABLE favorites_perfum DROP FOREIGN KEY FK_DAB25783D480961');
        $this->addSql('DROP TABLE perfumes');
        $this->addSql('DROP TABLE precio_contenido');
        $this->addSql('DROP TABLE stores');
        $this->addSql('DROP TABLE target_public');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE favorites_perfumes');
        $this->addSql('DROP TABLE favorites_perfum');
    }
}
