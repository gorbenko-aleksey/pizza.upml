<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181025182050 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE document_income_ingredient ADD document_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE document_income_ingredient ADD CONSTRAINT FK_B9840410C33F7837 FOREIGN KEY (document_id) REFERENCES document_income (id)');
        $this->addSql('CREATE INDEX IDX_B9840410C33F7837 ON document_income_ingredient (document_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE document_income_ingredient DROP FOREIGN KEY FK_B9840410C33F7837');
        $this->addSql('DROP INDEX IDX_B9840410C33F7837 ON document_income_ingredient');
        $this->addSql('ALTER TABLE document_income_ingredient DROP document_id');
    }
}
