<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220507133915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE map_element (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE document ADD map_element_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76A606F2A FOREIGN KEY (map_element_id) REFERENCES map_element (id)');
        $this->addSql('CREATE INDEX IDX_D8698A76A606F2A ON document (map_element_id)');
        $this->addSql('ALTER TABLE step DROP title');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76A606F2A');
        $this->addSql('DROP TABLE map_element');
        $this->addSql('DROP INDEX IDX_D8698A76A606F2A ON document');
        $this->addSql('ALTER TABLE document DROP map_element_id');
        $this->addSql('ALTER TABLE step ADD title VARCHAR(255) DEFAULT NULL');
    }
}
