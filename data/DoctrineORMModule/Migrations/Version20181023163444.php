<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181023163444 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO `user` (`id`, `email`, `password`, `first_name`, `last_name`, `created_at`, `changed_at`) VALUES
                        (1, 'gorbenko.aleksey@gmail.com', '$2y$14$08BRADisvs5BDUv.4sAheuqIjDTA.kADPLDOdaIBN8eyNySbDTc3K', 'Aleksey', 'Gorbenko', NOW(), NOW());");
        $this->addSql("INSERT INTO `user_role` (`id`, `parent_id`, `role_id`, `name`) VALUES
                        (1, NULL, 'guest', 'Гость'),
                        (2, 1, 'user', 'Пользователь'),
                        (3, 2, 'operator', 'Оператор'),
                        (4, 2, 'сook', 'Повар'),
                        (5, 2, 'driver', 'Водитель'),
                        (6, 2, 'admin', 'Администратор');
                        ");
        $this->addSql("INSERT INTO `user_role_ref` (`user_id`, `role_id`) VALUES (1, 6);");
        $this->addSql("INSERT INTO white_ip (`id`, `ip`, `comment`, `created_at`, `changed_at`) VALUES (1, '*.*.*.*', 'Any ip', NOW(), NOW());");
        $this->addSql("INSERT INTO `category` (`id`, `code`, `lft`, `rgt`, `lvl`, `status`, `name`, `root`) VALUES ('1', 'root', '1', '1', '0', '1', 'Root', '1');");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
