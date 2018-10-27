<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181027134658 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE document_income_ingredient DROP FOREIGN KEY FK_B9840410933FE08C');
        $this->addSql('ALTER TABLE document_income_ingredient ADD CONSTRAINT FK_B9840410933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE document_income_ingredient DROP FOREIGN KEY FK_B9840410933FE08C');
        $this->addSql('ALTER TABLE document_income_ingredient ADD CONSTRAINT FK_B9840410933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
    }
}
