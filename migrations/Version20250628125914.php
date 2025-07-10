<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250628125914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase_amenity ADD purchase_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase_amenity ADD CONSTRAINT FK_B0BAB31A558FBEB9 FOREIGN KEY (purchase_id) REFERENCES purchase (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B0BAB31A558FBEB9 ON purchase_amenity (purchase_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchased_ticket ADD purchase_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchased_ticket ADD CONSTRAINT FK_ADEECF72558FBEB9 FOREIGN KEY (purchase_id) REFERENCES purchase (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_ADEECF72558FBEB9 ON purchased_ticket (purchase_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket_usage ADD purchased_ticket_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket_usage ADD CONSTRAINT FK_F8DB3DFB3AE47D5D FOREIGN KEY (purchased_ticket_id) REFERENCES purchased_ticket (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F8DB3DFB3AE47D5D ON ticket_usage (purchased_ticket_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_account DROP FOREIGN KEY FK_253B48AE1C7DC1CE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_253B48AE1C7DC1CE ON user_account
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_account CHANGE user_details_id user_details INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_account ADD CONSTRAINT FK_253B48AE2A2B1580 FOREIGN KEY (user_details) REFERENCES user_details (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_253B48AE2A2B1580 ON user_account (user_details)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_role ADD since_date VARCHAR(255) DEFAULT NULL, ADD user_id INT NOT NULL, ADD role_id INT NOT NULL, DROP id, DROP PRIMARY KEY, ADD PRIMARY KEY (user_id, role_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3A76ED395 FOREIGN KEY (user_id) REFERENCES user_account (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2DE8C6A3A76ED395 ON user_role (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2DE8C6A3D60322AC ON user_role (role_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE purchased_ticket DROP FOREIGN KEY FK_ADEECF72558FBEB9
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_ADEECF72558FBEB9 ON purchased_ticket
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchased_ticket DROP purchase_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase_amenity DROP FOREIGN KEY FK_B0BAB31A558FBEB9
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B0BAB31A558FBEB9 ON purchase_amenity
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase_amenity DROP purchase_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket_usage DROP FOREIGN KEY FK_F8DB3DFB3AE47D5D
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_F8DB3DFB3AE47D5D ON ticket_usage
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket_usage DROP purchased_ticket_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_account DROP FOREIGN KEY FK_253B48AE2A2B1580
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_253B48AE2A2B1580 ON user_account
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_account CHANGE user_details user_details_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_account ADD CONSTRAINT FK_253B48AE1C7DC1CE FOREIGN KEY (user_details_id) REFERENCES user_details (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_253B48AE1C7DC1CE ON user_account (user_details_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3D60322AC
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_2DE8C6A3A76ED395 ON user_role
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_2DE8C6A3D60322AC ON user_role
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_role ADD id INT AUTO_INCREMENT NOT NULL, DROP since_date, DROP user_id, DROP role_id, DROP PRIMARY KEY, ADD PRIMARY KEY (id)
        SQL);
    }
}
