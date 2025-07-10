<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250628132335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_review ADD user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_review ADD CONSTRAINT FK_9ADA35DEA76ED395 FOREIGN KEY (user_id) REFERENCES user_account (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9ADA35DEA76ED395 ON edition_review (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase ADD user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase ADD CONSTRAINT FK_6117D13BA76ED395 FOREIGN KEY (user_id) REFERENCES user_account (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6117D13BA76ED395 ON purchase (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket_usage ADD staff_member_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket_usage ADD CONSTRAINT FK_F8DB3DFB44DB03B1 FOREIGN KEY (staff_member_id) REFERENCES user_account (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F8DB3DFB44DB03B1 ON ticket_usage (staff_member_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_review DROP FOREIGN KEY FK_9ADA35DEA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9ADA35DEA76ED395 ON edition_review
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_review DROP user_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13BA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_6117D13BA76ED395 ON purchase
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase DROP user_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket_usage DROP FOREIGN KEY FK_F8DB3DFB44DB03B1
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_F8DB3DFB44DB03B1 ON ticket_usage
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket_usage DROP staff_member_id
        SQL);
    }
}
