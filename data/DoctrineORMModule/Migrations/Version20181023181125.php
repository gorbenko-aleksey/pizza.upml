<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181023181125 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_image DROP FOREIGN KEY FK_64617F0361220EA6');
        $this->addSql('ALTER TABLE product_image DROP FOREIGN KEY FK_64617F03E53CD0CE');
        $this->addSql('DROP INDEX IDX_64617F0361220EA6 ON product_image');
        $this->addSql('DROP INDEX IDX_64617F03E53CD0CE ON product_image');
        $this->addSql('ALTER TABLE product_image DROP creator_id, DROP changer_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_image ADD creator_id INT UNSIGNED DEFAULT NULL, ADD changer_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE product_image ADD CONSTRAINT FK_64617F0361220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product_image ADD CONSTRAINT FK_64617F03E53CD0CE FOREIGN KEY (changer_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_64617F0361220EA6 ON product_image (creator_id)');
        $this->addSql('CREATE INDEX IDX_64617F03E53CD0CE ON product_image (changer_id)');
    }
}
