<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210708083725 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE author ADD authenticated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE author ADD CONSTRAINT FK_BDAFD8C8C3E01A26 FOREIGN KEY (authenticated_by_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BDAFD8C8C3E01A26 ON author (authenticated_by_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE author DROP FOREIGN KEY FK_BDAFD8C8C3E01A26');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX UNIQ_BDAFD8C8C3E01A26 ON author');
        $this->addSql('ALTER TABLE author DROP authenticated_by_id');
    }
}
