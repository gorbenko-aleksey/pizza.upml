<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181023163443 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT UNSIGNED AUTO_INCREMENT NOT NULL, creator_id INT UNSIGNED DEFAULT NULL, changer_id INT UNSIGNED DEFAULT NULL, status INT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, secret VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) DEFAULT NULL, password_changed_at DATETIME DEFAULT NULL, password_change_session_id CHAR(40) DEFAULT NULL, created_at DATETIME NOT NULL, changed_at DATETIME NOT NULL, INDEX IDX_8D93D64961220EA6 (creator_id), INDEX IDX_8D93D649E53CD0CE (changer_id), UNIQUE INDEX user_email (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role_ref (user_id INT UNSIGNED NOT NULL, role_id INT UNSIGNED NOT NULL, INDEX IDX_40805ED1A76ED395 (user_id), INDEX IDX_40805ED1D60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE email_queue_log (id INT UNSIGNED AUTO_INCREMENT NOT NULL, message LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, created_at DATETIME NOT NULL, changed_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE white_ip (id INT UNSIGNED AUTO_INCREMENT NOT NULL, creator_id INT UNSIGNED DEFAULT NULL, changer_id INT UNSIGNED DEFAULT NULL, ip VARCHAR(32) NOT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, changed_at DATETIME NOT NULL, INDEX IDX_D765929161220EA6 (creator_id), INDEX IDX_D7659291E53CD0CE (changer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_image (id INT UNSIGNED AUTO_INCREMENT NOT NULL, product_id INT UNSIGNED DEFAULT NULL, creator_id INT UNSIGNED DEFAULT NULL, changer_id INT UNSIGNED DEFAULT NULL, path VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, mime_type VARCHAR(255) NOT NULL, size NUMERIC(10, 0) NOT NULL, created_at DATETIME NOT NULL, changed_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_64617F034584665A (product_id), INDEX IDX_64617F0361220EA6 (creator_id), INDEX IDX_64617F03E53CD0CE (changer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE receipt_ingredient_weight (id INT UNSIGNED AUTO_INCREMENT NOT NULL, ingredient_id INT UNSIGNED DEFAULT NULL, receipt_id INT UNSIGNED DEFAULT NULL, creator_id INT UNSIGNED DEFAULT NULL, changer_id INT UNSIGNED DEFAULT NULL, weight DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, changed_at DATETIME NOT NULL, INDEX IDX_A56AB6FF933FE08C (ingredient_id), INDEX IDX_A56AB6FF2B5CA896 (receipt_id), INDEX IDX_A56AB6FF61220EA6 (creator_id), INDEX IDX_A56AB6FFE53CD0CE (changer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE email_queue (id INT UNSIGNED AUTO_INCREMENT NOT NULL, message LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:object)\', exception_trace TEXT DEFAULT NULL, priority SMALLINT NOT NULL, created_at DATETIME NOT NULL, changed_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site_map (id INT UNSIGNED AUTO_INCREMENT NOT NULL, changer_id INT UNSIGNED DEFAULT NULL, body LONGTEXT NOT NULL, created_at DATETIME NOT NULL, changed_at DATETIME NOT NULL, INDEX IDX_67309B9BE53CD0CE (changer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT UNSIGNED AUTO_INCREMENT NOT NULL, status SMALLINT NOT NULL, address LONGTEXT DEFAULT NULL, phone LONGTEXT DEFAULT NULL, notes LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, changed_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE robots (id INT UNSIGNED AUTO_INCREMENT NOT NULL, changer_id INT UNSIGNED DEFAULT NULL, body LONGTEXT NOT NULL, created_at DATETIME NOT NULL, changed_at DATETIME NOT NULL, INDEX IDX_BCB1C214E53CD0CE (changer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page (id INT UNSIGNED AUTO_INCREMENT NOT NULL, creator_id INT UNSIGNED DEFAULT NULL, changer_id INT UNSIGNED DEFAULT NULL, status SMALLINT NOT NULL, name TINYTEXT DEFAULT NULL, code TINYTEXT DEFAULT NULL, driver TINYTEXT DEFAULT NULL, short_description TINYTEXT DEFAULT NULL, full_description LONGTEXT DEFAULT NULL, html_title TEXT DEFAULT NULL, meta_description LONGTEXT DEFAULT NULL, meta_keywords LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, changed_at DATETIME NOT NULL, INDEX IDX_140AB62061220EA6 (creator_id), INDEX IDX_140AB620E53CD0CE (changer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredient (id INT UNSIGNED AUTO_INCREMENT NOT NULL, creator_id INT UNSIGNED DEFAULT NULL, changer_id INT UNSIGNED DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, changed_at DATETIME NOT NULL, INDEX IDX_6BAF787061220EA6 (creator_id), INDEX IDX_6BAF7870E53CD0CE (changer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role (id INT UNSIGNED AUTO_INCREMENT NOT NULL, parent_id INT UNSIGNED DEFAULT NULL, role_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, INDEX parent_id (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT UNSIGNED AUTO_INCREMENT NOT NULL, parent_id INT UNSIGNED DEFAULT NULL, creator_id INT UNSIGNED DEFAULT NULL, changer_id INT UNSIGNED DEFAULT NULL, lft INT NOT NULL, rgt INT NOT NULL, lvl INT NOT NULL, code VARCHAR(255) NOT NULL, status INT NOT NULL, name VARCHAR(255) NOT NULL, root INT NOT NULL, description_short LONGTEXT DEFAULT NULL, description_full LONGTEXT DEFAULT NULL, html_title LONGTEXT DEFAULT NULL, meta_keywords LONGTEXT DEFAULT NULL, meta_description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, changed_at DATETIME NOT NULL, INDEX IDX_64C19C161220EA6 (creator_id), INDEX IDX_64C19C1E53CD0CE (changer_id), INDEX FK_category_category_id (parent_id), UNIQUE INDEX category_code (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_product (id INT UNSIGNED AUTO_INCREMENT NOT NULL, order_id INT UNSIGNED DEFAULT NULL, hash VARCHAR(255) DEFAULT NULL, productParams LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', receiptParams LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', quantity INT NOT NULL, price NUMERIC(10, 2) NOT NULL, created_at DATETIME NOT NULL, changed_at DATETIME NOT NULL, INDEX IDX_2530ADE68D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT UNSIGNED AUTO_INCREMENT NOT NULL, category_id INT UNSIGNED DEFAULT NULL, receipt_id INT UNSIGNED DEFAULT NULL, creator_id INT UNSIGNED DEFAULT NULL, changer_id INT UNSIGNED DEFAULT NULL, status INT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, price NUMERIC(10, 2) NOT NULL, short_description LONGTEXT DEFAULT NULL, full_description LONGTEXT DEFAULT NULL, html_title VARCHAR(255) DEFAULT NULL, meta_keywords VARCHAR(255) DEFAULT NULL, meta_description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, changed_at DATETIME NOT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), INDEX IDX_D34A04AD2B5CA896 (receipt_id), INDEX IDX_D34A04AD61220EA6 (creator_id), INDEX IDX_D34A04ADE53CD0CE (changer_id), UNIQUE INDEX product_code (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seo (id INT UNSIGNED AUTO_INCREMENT NOT NULL, creator_id INT UNSIGNED DEFAULT NULL, changer_id INT UNSIGNED DEFAULT NULL, url LONGTEXT NOT NULL, status INT NOT NULL, sort INT DEFAULT NULL, title LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, keywords LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, changed_at DATETIME NOT NULL, INDEX IDX_6C71EC3061220EA6 (creator_id), INDEX IDX_6C71EC30E53CD0CE (changer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE receipt (id INT UNSIGNED AUTO_INCREMENT NOT NULL, creator_id INT UNSIGNED DEFAULT NULL, changer_id INT UNSIGNED DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, product_weight DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, changed_at DATETIME NOT NULL, INDEX IDX_5399B64561220EA6 (creator_id), INDEX IDX_5399B645E53CD0CE (changer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64961220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649E53CD0CE FOREIGN KEY (changer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_role_ref ADD CONSTRAINT FK_40805ED1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_role_ref ADD CONSTRAINT FK_40805ED1D60322AC FOREIGN KEY (role_id) REFERENCES user_role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE white_ip ADD CONSTRAINT FK_D765929161220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE white_ip ADD CONSTRAINT FK_D7659291E53CD0CE FOREIGN KEY (changer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product_image ADD CONSTRAINT FK_64617F034584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_image ADD CONSTRAINT FK_64617F0361220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product_image ADD CONSTRAINT FK_64617F03E53CD0CE FOREIGN KEY (changer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE receipt_ingredient_weight ADD CONSTRAINT FK_A56AB6FF933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('ALTER TABLE receipt_ingredient_weight ADD CONSTRAINT FK_A56AB6FF2B5CA896 FOREIGN KEY (receipt_id) REFERENCES receipt (id)');
        $this->addSql('ALTER TABLE receipt_ingredient_weight ADD CONSTRAINT FK_A56AB6FF61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE receipt_ingredient_weight ADD CONSTRAINT FK_A56AB6FFE53CD0CE FOREIGN KEY (changer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE site_map ADD CONSTRAINT FK_67309B9BE53CD0CE FOREIGN KEY (changer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE robots ADD CONSTRAINT FK_BCB1C214E53CD0CE FOREIGN KEY (changer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB62061220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620E53CD0CE FOREIGN KEY (changer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF787061220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF7870E53CD0CE FOREIGN KEY (changer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3727ACA70 FOREIGN KEY (parent_id) REFERENCES user_role (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C161220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1E53CD0CE FOREIGN KEY (changer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE68D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD2B5CA896 FOREIGN KEY (receipt_id) REFERENCES receipt (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADE53CD0CE FOREIGN KEY (changer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE seo ADD CONSTRAINT FK_6C71EC3061220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE seo ADD CONSTRAINT FK_6C71EC30E53CD0CE FOREIGN KEY (changer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE receipt ADD CONSTRAINT FK_5399B64561220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE receipt ADD CONSTRAINT FK_5399B645E53CD0CE FOREIGN KEY (changer_id) REFERENCES user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64961220EA6');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649E53CD0CE');
        $this->addSql('ALTER TABLE user_role_ref DROP FOREIGN KEY FK_40805ED1A76ED395');
        $this->addSql('ALTER TABLE white_ip DROP FOREIGN KEY FK_D765929161220EA6');
        $this->addSql('ALTER TABLE white_ip DROP FOREIGN KEY FK_D7659291E53CD0CE');
        $this->addSql('ALTER TABLE product_image DROP FOREIGN KEY FK_64617F0361220EA6');
        $this->addSql('ALTER TABLE product_image DROP FOREIGN KEY FK_64617F03E53CD0CE');
        $this->addSql('ALTER TABLE receipt_ingredient_weight DROP FOREIGN KEY FK_A56AB6FF61220EA6');
        $this->addSql('ALTER TABLE receipt_ingredient_weight DROP FOREIGN KEY FK_A56AB6FFE53CD0CE');
        $this->addSql('ALTER TABLE site_map DROP FOREIGN KEY FK_67309B9BE53CD0CE');
        $this->addSql('ALTER TABLE robots DROP FOREIGN KEY FK_BCB1C214E53CD0CE');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB62061220EA6');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB620E53CD0CE');
        $this->addSql('ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF787061220EA6');
        $this->addSql('ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF7870E53CD0CE');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C161220EA6');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1E53CD0CE');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD61220EA6');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADE53CD0CE');
        $this->addSql('ALTER TABLE seo DROP FOREIGN KEY FK_6C71EC3061220EA6');
        $this->addSql('ALTER TABLE seo DROP FOREIGN KEY FK_6C71EC30E53CD0CE');
        $this->addSql('ALTER TABLE receipt DROP FOREIGN KEY FK_5399B64561220EA6');
        $this->addSql('ALTER TABLE receipt DROP FOREIGN KEY FK_5399B645E53CD0CE');
        $this->addSql('ALTER TABLE order_product DROP FOREIGN KEY FK_2530ADE68D9F6D38');
        $this->addSql('ALTER TABLE receipt_ingredient_weight DROP FOREIGN KEY FK_A56AB6FF933FE08C');
        $this->addSql('ALTER TABLE user_role_ref DROP FOREIGN KEY FK_40805ED1D60322AC');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3727ACA70');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1727ACA70');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE product_image DROP FOREIGN KEY FK_64617F034584665A');
        $this->addSql('ALTER TABLE receipt_ingredient_weight DROP FOREIGN KEY FK_A56AB6FF2B5CA896');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD2B5CA896');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_role_ref');
        $this->addSql('DROP TABLE email_queue_log');
        $this->addSql('DROP TABLE white_ip');
        $this->addSql('DROP TABLE product_image');
        $this->addSql('DROP TABLE receipt_ingredient_weight');
        $this->addSql('DROP TABLE email_queue');
        $this->addSql('DROP TABLE site_map');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE robots');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE user_role');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE order_product');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE seo');
        $this->addSql('DROP TABLE receipt');
    }
}
