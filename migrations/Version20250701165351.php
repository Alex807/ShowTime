<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250701165351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_review DROP FOREIGN KEY FK_9ADA35DEA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_review CHANGE user_id user_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_review ADD CONSTRAINT FK_9ADA35DEA76ED395 FOREIGN KEY (user_id) REFERENCES user_account (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_edition DROP FOREIGN KEY FK_8574210E8AEBAF57
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_edition CHANGE festival_id festival_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_edition ADD CONSTRAINT FK_8574210E8AEBAF57 FOREIGN KEY (festival_id) REFERENCES festival (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_account DROP FOREIGN KEY FK_253B48AE2A2B1580
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_account CHANGE user_details user_details INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_account ADD CONSTRAINT FK_253B48AE2A2B1580 FOREIGN KEY (user_details) REFERENCES user_details (id) ON DELETE SET NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_review DROP FOREIGN KEY FK_9ADA35DEA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_review CHANGE user_id user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_review ADD CONSTRAINT FK_9ADA35DEA76ED395 FOREIGN KEY (user_id) REFERENCES user_account (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_edition DROP FOREIGN KEY FK_8574210E8AEBAF57
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_edition CHANGE festival_id festival_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_edition ADD CONSTRAINT FK_8574210E8AEBAF57 FOREIGN KEY (festival_id) REFERENCES festival (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_account DROP FOREIGN KEY FK_253B48AE2A2B1580
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_account CHANGE user_details user_details INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_account ADD CONSTRAINT FK_253B48AE2A2B1580 FOREIGN KEY (user_details) REFERENCES user_details (id)
        SQL);
    }
}
