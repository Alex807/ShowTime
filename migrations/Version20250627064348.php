<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250627064348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_edition ADD festival_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_edition ADD CONSTRAINT FK_8574210E8AEBAF57 FOREIGN KEY (festival_id) REFERENCES festival (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8574210E8AEBAF57 ON festival_edition (festival_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_edition DROP FOREIGN KEY FK_8574210E8AEBAF57
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8574210E8AEBAF57 ON festival_edition
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_edition DROP festival_id
        SQL);
    }
}
