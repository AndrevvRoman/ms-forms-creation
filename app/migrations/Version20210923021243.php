<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210923021243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE field (id INT AUTO_INCREMENT NOT NULL, id_form_fk_id INT NOT NULL, is_require TINYINT(1) NOT NULL, title VARCHAR(255) DEFAULT NULL, place_holder VARCHAR(255) DEFAULT NULL, INDEX IDX_5BF5455883BA3DC4 (id_form_fk_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, role LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE field ADD CONSTRAINT FK_5BF5455883BA3DC4 FOREIGN KEY (id_form_fk_id) REFERENCES form (id)');
        $this->addSql('ALTER TABLE form ADD id_user_fk_id INT NOT NULL, ADD title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE form ADD CONSTRAINT FK_5288FD4FE23F625F FOREIGN KEY (id_user_fk_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5288FD4FE23F625F ON form (id_user_fk_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE form DROP FOREIGN KEY FK_5288FD4FE23F625F');
        $this->addSql('DROP TABLE field');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_5288FD4FE23F625F ON form');
        $this->addSql('ALTER TABLE form DROP id_user_fk_id, DROP title');
    }
}
