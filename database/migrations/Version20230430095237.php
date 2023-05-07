<?php

declare(strict_types=1);

namespace Clb\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230430095237 extends AbstractMigration
{
	public function getDescription(): string
	{
		return '';
	}

	public function up(Schema $schema): void
	{
		// this up() migration is auto-generated, please modify it to your needs
		$this->addSql('ALTER TABLE clb_config CHANGE created_at created_at DATETIME default current_timestamp, CHANGE updated_at updated_at DATETIME on update current_timestamp');
		$this->addSql('ALTER TABLE clb_groups CHANGE created_at created_at DATETIME default current_timestamp, CHANGE updated_at updated_at DATETIME on update current_timestamp');
		$this->addSql('ALTER TABLE clb_mail_accounts CHANGE created_at created_at DATETIME default current_timestamp, CHANGE updated_at updated_at DATETIME on update current_timestamp');
		$this->addSql('ALTER TABLE clb_mail_messages CHANGE created_at created_at DATETIME default current_timestamp, CHANGE updated_at updated_at DATETIME on update current_timestamp');
		$this->addSql('ALTER TABLE clb_permissions CHANGE created_at created_at DATETIME default current_timestamp, CHANGE updated_at updated_at DATETIME on update current_timestamp');
		$this->addSql('ALTER TABLE clb_users ADD language VARCHAR(255) AFTER `photo`, ADD organization VARCHAR(255) AFTER `language`, ADD position VARCHAR(255) AFTER `organization`, ADD phone VARCHAR(32) AFTER `position`, ADD about TEXT AFTER `phone`, CHANGE id id INT AUTO_INCREMENT NOT NULL UNIQUE, CHANGE created_at created_at DATETIME default current_timestamp, CHANGE updated_at updated_at DATETIME on update current_timestamp');
		$this->addSql('DROP INDEX user_id ON clb_users_groups');
		$this->addSql('ALTER TABLE clb_users_groups CHANGE user_id user_id INT AUTO_INCREMENT NOT NULL UNIQUE');
	}

	public function down(Schema $schema): void
	{
		// this down() migration is auto-generated, please modify it to your needs
		$this->addSql('ALTER TABLE `clb_config` CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
		$this->addSql('ALTER TABLE `clb_mail_messages` CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
		$this->addSql('ALTER TABLE clb_users_groups CHANGE user_id user_id INT AUTO_INCREMENT NOT NULL');
		$this->addSql('CREATE UNIQUE INDEX user_id ON clb_users_groups (user_id)');
		$this->addSql('ALTER TABLE `clb_groups` CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
		$this->addSql('ALTER TABLE `clb_permissions` CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
		$this->addSql('ALTER TABLE `clb_users` DROP language, DROP organization, DROP position, DROP phone, DROP about, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
		$this->addSql('ALTER TABLE `clb_mail_accounts` CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
	}
}
