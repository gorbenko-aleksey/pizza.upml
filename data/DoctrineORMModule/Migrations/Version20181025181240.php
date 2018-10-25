<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181025181240 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE document_income (id INT UNSIGNED AUTO_INCREMENT NOT NULL, creator_id INT UNSIGNED DEFAULT NULL, changer_id INT UNSIGNED DEFAULT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, changed_at DATETIME NOT NULL, INDEX IDX_81CAD3B661220EA6 (creator_id), INDEX IDX_81CAD3B6E53CD0CE (changer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_income_ingredient (id INT UNSIGNED AUTO_INCREMENT NOT NULL, ingredient_id INT UNSIGNED DEFAULT NULL, creator_id INT UNSIGNED DEFAULT NULL, changer_id INT UNSIGNED DEFAULT NULL, weight DOUBLE PRECISION NOT NULL, residue DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, changed_at DATETIME NOT NULL, INDEX IDX_B9840410933FE08C (ingredient_id), INDEX IDX_B984041061220EA6 (creator_id), INDEX IDX_B9840410E53CD0CE (changer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE document_income ADD CONSTRAINT FK_81CAD3B661220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE document_income ADD CONSTRAINT FK_81CAD3B6E53CD0CE FOREIGN KEY (changer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE document_income_ingredient ADD CONSTRAINT FK_B9840410933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('ALTER TABLE document_income_ingredient ADD CONSTRAINT FK_B984041061220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE document_income_ingredient ADD CONSTRAINT FK_B9840410E53CD0CE FOREIGN KEY (changer_id) REFERENCES user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE document_income');
        $this->addSql('DROP TABLE document_income_ingredient');
    }
}
