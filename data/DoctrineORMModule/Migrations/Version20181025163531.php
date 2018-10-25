<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181025163531 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE document_movement_ingredient (id INT UNSIGNED AUTO_INCREMENT NOT NULL, ingredient_id INT UNSIGNED DEFAULT NULL, document_id INT UNSIGNED DEFAULT NULL, weight DOUBLE PRECISION NOT NULL, INDEX IDX_C82D81EF933FE08C (ingredient_id), INDEX IDX_C82D81EFC33F7837 (document_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_write_off (id INT UNSIGNED AUTO_INCREMENT NOT NULL, ingredient_id INT UNSIGNED DEFAULT NULL, movement_id INT UNSIGNED DEFAULT NULL, creator_id INT UNSIGNED DEFAULT NULL, changer_id INT UNSIGNED DEFAULT NULL, weight DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, changed_at DATETIME NOT NULL, INDEX IDX_165A2187933FE08C (ingredient_id), INDEX IDX_165A2187229E70A7 (movement_id), INDEX IDX_165A218761220EA6 (creator_id), INDEX IDX_165A2187E53CD0CE (changer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_movement_type (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_movement (id INT UNSIGNED AUTO_INCREMENT NOT NULL, type_id INT UNSIGNED DEFAULT NULL, operator_id INT UNSIGNED DEFAULT NULL, creator_id INT UNSIGNED DEFAULT NULL, changer_id INT UNSIGNED DEFAULT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, changed_at DATETIME NOT NULL, INDEX IDX_7B1C64AEC54C8C93 (type_id), INDEX IDX_7B1C64AE584598A3 (operator_id), INDEX IDX_7B1C64AE61220EA6 (creator_id), INDEX IDX_7B1C64AEE53CD0CE (changer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_income (id INT UNSIGNED AUTO_INCREMENT NOT NULL, creator_id INT UNSIGNED DEFAULT NULL, changer_id INT UNSIGNED DEFAULT NULL, status INT NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, changed_at DATETIME NOT NULL, INDEX IDX_81CAD3B661220EA6 (creator_id), INDEX IDX_81CAD3B6E53CD0CE (changer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_income_ingredient (id INT UNSIGNED AUTO_INCREMENT NOT NULL, ingredient_id INT UNSIGNED DEFAULT NULL, document_id INT UNSIGNED DEFAULT NULL, weight DOUBLE PRECISION NOT NULL, residue DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_B9840410933FE08C (ingredient_id), INDEX IDX_B9840410C33F7837 (document_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE document_movement_ingredient ADD CONSTRAINT FK_C82D81EF933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('ALTER TABLE document_movement_ingredient ADD CONSTRAINT FK_C82D81EFC33F7837 FOREIGN KEY (document_id) REFERENCES document_movement (id)');
        $this->addSql('ALTER TABLE document_write_off ADD CONSTRAINT FK_165A2187933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('ALTER TABLE document_write_off ADD CONSTRAINT FK_165A2187229E70A7 FOREIGN KEY (movement_id) REFERENCES document_movement (id)');
        $this->addSql('ALTER TABLE document_write_off ADD CONSTRAINT FK_165A218761220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE document_write_off ADD CONSTRAINT FK_165A2187E53CD0CE FOREIGN KEY (changer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE document_movement ADD CONSTRAINT FK_7B1C64AEC54C8C93 FOREIGN KEY (type_id) REFERENCES document_movement_type (id)');
        $this->addSql('ALTER TABLE document_movement ADD CONSTRAINT FK_7B1C64AE584598A3 FOREIGN KEY (operator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE document_movement ADD CONSTRAINT FK_7B1C64AE61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE document_movement ADD CONSTRAINT FK_7B1C64AEE53CD0CE FOREIGN KEY (changer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE document_income ADD CONSTRAINT FK_81CAD3B661220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE document_income ADD CONSTRAINT FK_81CAD3B6E53CD0CE FOREIGN KEY (changer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE document_income_ingredient ADD CONSTRAINT FK_B9840410933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('ALTER TABLE document_income_ingredient ADD CONSTRAINT FK_B9840410C33F7837 FOREIGN KEY (document_id) REFERENCES document_income (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE document_movement DROP FOREIGN KEY FK_7B1C64AEC54C8C93');
        $this->addSql('ALTER TABLE document_movement_ingredient DROP FOREIGN KEY FK_C82D81EFC33F7837');
        $this->addSql('ALTER TABLE document_write_off DROP FOREIGN KEY FK_165A2187229E70A7');
        $this->addSql('ALTER TABLE document_income_ingredient DROP FOREIGN KEY FK_B9840410C33F7837');
        $this->addSql('DROP TABLE document_movement_ingredient');
        $this->addSql('DROP TABLE document_write_off');
        $this->addSql('DROP TABLE document_movement_type');
        $this->addSql('DROP TABLE document_movement');
        $this->addSql('DROP TABLE document_income');
        $this->addSql('DROP TABLE document_income_ingredient');
    }
}
