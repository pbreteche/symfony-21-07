<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210707081353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('INSERT INTO author (`name`, `email`) VALUES (\'unknow\', \'unknow@nowhere.com\')');
        $this->addSql('ALTER TABLE article ADD written_by_id INT DEFAULT NULL');
        $this->addSql('UPDATE article SET written_by_id = 1');
        $this->addSql('ALTER TABLE article CHANGE written_by_id written_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66AB69C8EF FOREIGN KEY (written_by_id) REFERENCES author (id)');
        $this->addSql('CREATE INDEX IDX_23A0E66AB69C8EF ON article (written_by_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66AB69C8EF');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP INDEX IDX_23A0E66AB69C8EF ON article');
        $this->addSql('ALTER TABLE article DROP written_by_id');
    }
}
