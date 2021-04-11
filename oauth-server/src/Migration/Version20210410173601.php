<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210410173601 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE access_tokens (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, client_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, scopes LONGTEXT DEFAULT NULL, revoked TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, expires_at DATETIME DEFAULT NULL, INDEX IDX_58D184BCA76ED395 (user_id), INDEX IDX_58D184BC19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE auth_codes (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, user_id INT DEFAULT NULL, scopes LONGTEXT DEFAULT NULL, revoked TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, INDEX IDX_298F903819EB6921 (client_id), INDEX IDX_298F9038A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clients (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, secret VARCHAR(255) NOT NULL, redirect VARCHAR(255) NOT NULL, personal_access_client TINYINT(1) NOT NULL, password_client TINYINT(1) NOT NULL, revoked TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, access_token_id INT DEFAULT NULL, revoked TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, INDEX access_token (access_token_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, name VARCHAR(255) NOT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9AA08CB10 (login), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE access_tokens ADD CONSTRAINT FK_58D184BCA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE access_tokens ADD CONSTRAINT FK_58D184BC19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE auth_codes ADD CONSTRAINT FK_298F903819EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE auth_codes ADD CONSTRAINT FK_298F9038A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE refresh_tokens ADD CONSTRAINT FK_9BACE7E12CCB2688 FOREIGN KEY (access_token_id) REFERENCES access_tokens (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE refresh_tokens DROP FOREIGN KEY FK_9BACE7E12CCB2688');
        $this->addSql('ALTER TABLE access_tokens DROP FOREIGN KEY FK_58D184BC19EB6921');
        $this->addSql('ALTER TABLE auth_codes DROP FOREIGN KEY FK_298F903819EB6921');
        $this->addSql('ALTER TABLE access_tokens DROP FOREIGN KEY FK_58D184BCA76ED395');
        $this->addSql('ALTER TABLE auth_codes DROP FOREIGN KEY FK_298F9038A76ED395');
        $this->addSql('DROP TABLE access_tokens');
        $this->addSql('DROP TABLE auth_codes');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE users');
    }
}
