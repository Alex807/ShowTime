<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250710175712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE artist (id INT AUTO_INCREMENT NOT NULL, real_name VARCHAR(255) NOT NULL, stage_name VARCHAR(100) NOT NULL, music_genre VARCHAR(50) NOT NULL, instagram_account VARCHAR(100) NOT NULL, image_url VARCHAR(100) DEFAULT NULL, manager_email VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE edition_artist (id INT AUTO_INCREMENT NOT NULL, is_headliner TINYINT(1) NOT NULL, performance_date DATE NOT NULL, start_time TIME NOT NULL, end_time TIME NOT NULL, edition_id INT NOT NULL, artist_id INT NOT NULL, INDEX IDX_E2C0229F74281A5E (edition_id), INDEX IDX_E2C0229FB7970CF8 (artist_id), UNIQUE INDEX not_overlapped_artist_performances (edition_id, artist_id, performance_date, start_time, end_time), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE edition_review (id INT AUTO_INCREMENT NOT NULL, rating_stars INT NOT NULL, comment LONGTEXT NOT NULL, posted_at DATETIME NOT NULL, edition_id INT NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_9ADA35DE74281A5E (edition_id), INDEX IDX_9ADA35DEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE festival (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, country VARCHAR(100) NOT NULL, city VARCHAR(100) NOT NULL, street_name VARCHAR(255) NOT NULL, street_no INT NOT NULL, festival_email VARCHAR(255) NOT NULL, website VARCHAR(255) DEFAULT NULL, logo_url VARCHAR(100) DEFAULT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE festival_edition (id INT AUTO_INCREMENT NOT NULL, year_happened INT NOT NULL, venue_name VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, status VARCHAR(30) NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, people_capacity INT NOT NULL, terms_conditions LONGTEXT NOT NULL, updated_at DATETIME NOT NULL, festival_id INT NOT NULL, INDEX IDX_8574210E8AEBAF57 (festival_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE purchase (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, purchase_date DATETIME NOT NULL, edition_id INT NOT NULL, user_id INT NOT NULL, ticket_type_id INT NOT NULL, INDEX IDX_6117D13B74281A5E (edition_id), INDEX IDX_6117D13BA76ED395 (user_id), INDEX IDX_6117D13BC980D5C1 (ticket_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE ticket_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, benefits LONGTEXT DEFAULT NULL, price NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_account (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(150) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, password_token VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_details (first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, age INT NOT NULL, phone_no VARCHAR(20) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, user_id INT NOT NULL, PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_artist ADD CONSTRAINT FK_E2C0229F74281A5E FOREIGN KEY (edition_id) REFERENCES festival_edition (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_artist ADD CONSTRAINT FK_E2C0229FB7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_review ADD CONSTRAINT FK_9ADA35DE74281A5E FOREIGN KEY (edition_id) REFERENCES festival_edition (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_review ADD CONSTRAINT FK_9ADA35DEA76ED395 FOREIGN KEY (user_id) REFERENCES user_account (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_edition ADD CONSTRAINT FK_8574210E8AEBAF57 FOREIGN KEY (festival_id) REFERENCES festival (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B74281A5E FOREIGN KEY (edition_id) REFERENCES festival_edition (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase ADD CONSTRAINT FK_6117D13BA76ED395 FOREIGN KEY (user_id) REFERENCES user_account (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase ADD CONSTRAINT FK_6117D13BC980D5C1 FOREIGN KEY (ticket_type_id) REFERENCES ticket_type (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_details ADD CONSTRAINT FK_2A2B1580A76ED395 FOREIGN KEY (user_id) REFERENCES user_account (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_artist DROP FOREIGN KEY FK_E2C0229F74281A5E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_artist DROP FOREIGN KEY FK_E2C0229FB7970CF8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_review DROP FOREIGN KEY FK_9ADA35DE74281A5E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_review DROP FOREIGN KEY FK_9ADA35DEA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_edition DROP FOREIGN KEY FK_8574210E8AEBAF57
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13B74281A5E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13BA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13BC980D5C1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_details DROP FOREIGN KEY FK_2A2B1580A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE artist
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE edition_artist
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE edition_review
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE festival
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE festival_edition
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE purchase
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ticket_type
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_account
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_details
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
