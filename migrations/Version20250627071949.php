<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250627071949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_amenity ADD amenity_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_amenity ADD CONSTRAINT FK_B8EF78819F9F1305 FOREIGN KEY (amenity_id) REFERENCES amenity (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B8EF78819F9F1305 ON edition_amenity (amenity_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_artist ADD artist_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_artist ADD CONSTRAINT FK_E2C0229FB7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E2C0229FB7970CF8 ON edition_artist (artist_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase_amenity ADD amenity_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase_amenity ADD CONSTRAINT FK_B0BAB31A9F9F1305 FOREIGN KEY (amenity_id) REFERENCES amenity (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B0BAB31A9F9F1305 ON purchase_amenity (amenity_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchased_ticket ADD ticket_type_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchased_ticket ADD CONSTRAINT FK_ADEECF72C980D5C1 FOREIGN KEY (ticket_type_id) REFERENCES ticket_type (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_ADEECF72C980D5C1 ON purchased_ticket (ticket_type_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_amenity DROP FOREIGN KEY FK_B8EF78819F9F1305
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B8EF78819F9F1305 ON edition_amenity
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_amenity DROP amenity_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_artist DROP FOREIGN KEY FK_E2C0229FB7970CF8
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_E2C0229FB7970CF8 ON edition_artist
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_artist DROP artist_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchased_ticket DROP FOREIGN KEY FK_ADEECF72C980D5C1
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_ADEECF72C980D5C1 ON purchased_ticket
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchased_ticket DROP ticket_type_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase_amenity DROP FOREIGN KEY FK_B0BAB31A9F9F1305
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B0BAB31A9F9F1305 ON purchase_amenity
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase_amenity DROP amenity_id
        SQL);
    }
}
