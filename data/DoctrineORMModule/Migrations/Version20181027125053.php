<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181027125053 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE document_income_ingredient DROP FOREIGN KEY FK_B984041061220EA6');
        $this->addSql('ALTER TABLE document_income_ingredient DROP FOREIGN KEY FK_B9840410E53CD0CE');
        $this->addSql('DROP INDEX IDX_B984041061220EA6 ON document_income_ingredient');
        $this->addSql('DROP INDEX IDX_B9840410E53CD0CE ON document_income_ingredient');
        $this->addSql('ALTER TABLE document_income_ingredient DROP creator_id, DROP changer_id, DROP created_at, DROP changed_at');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE document_income_ingredient ADD creator_id INT UNSIGNED DEFAULT NULL, ADD changer_id INT UNSIGNED DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD changed_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE document_income_ingredient ADD CONSTRAINT FK_B984041061220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE document_income_ingredient ADD CONSTRAINT FK_B9840410E53CD0CE FOREIGN KEY (changer_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B984041061220EA6 ON document_income_ingredient (creator_id)');
        $this->addSql('CREATE INDEX IDX_B9840410E53CD0CE ON document_income_ingredient (changer_id)');
    }
}
