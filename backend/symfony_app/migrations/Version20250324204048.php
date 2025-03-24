<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250324204048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE job_interviews (id VARCHAR(255) NOT NULL, job_application_id VARCHAR(255) NOT NULL, interview_type VARCHAR(255) NOT NULL, interview_date VARCHAR(255) NOT NULL, was_held TINYINT(1) NOT NULL, INDEX IDX_5002B69FAC7A5A08 (job_application_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE job_interviews ADD CONSTRAINT FK_5002B69FAC7A5A08 FOREIGN KEY (job_application_id) REFERENCES job_applications (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job_interviews DROP FOREIGN KEY FK_5002B69FAC7A5A08');
        $this->addSql('DROP TABLE job_interviews');
    }
}
