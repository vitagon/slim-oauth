<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210423173902 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE access_tokens DROP FOREIGN KEY FK_58D184BCA76ED395');
        $this->addSql('ALTER TABLE auth_codes DROP FOREIGN KEY FK_298F9038A76ED395');

        $this->addSql('ALTER TABLE auth_codes CHANGE id id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE users CHANGE id id INT AUTO_INCREMENT NOT NULL');

        $this->addSql('ALTER TABLE access_tokens ADD CONSTRAINT FK_58D184BCA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE auth_codes ADD CONSTRAINT FK_298F9038A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE access_tokens DROP FOREIGN KEY FK_58D184BCA76ED395');
        $this->addSql('ALTER TABLE auth_codes DROP FOREIGN KEY FK_298F9038A76ED395');

        $this->addSql('ALTER TABLE auth_codes CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE users CHANGE id id INT NOT NULL');

        $this->addSql('ALTER TABLE access_tokens ADD CONSTRAINT FK_58D184BCA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE auth_codes ADD CONSTRAINT FK_298F9038A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
