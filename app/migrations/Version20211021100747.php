<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211021100747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE field (id INT AUTO_INCREMENT NOT NULL, id_form_fk_id INT NOT NULL, is_require TINYINT(1) NOT NULL, title VARCHAR(255) DEFAULT NULL, place_holder VARCHAR(255) DEFAULT NULL, input_type VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, possble_values LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_5BF5455883BA3DC4 (id_form_fk_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, user_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE response (id INT AUTO_INCREMENT NOT NULL, form_id_fk_id INT NOT NULL, response_body JSON NOT NULL, INDEX IDX_3E7B0BFBFAC8DDA4 (form_id_fk_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE field ADD CONSTRAINT FK_5BF5455883BA3DC4 FOREIGN KEY (id_form_fk_id) REFERENCES form (id)');
        $this->addSql('ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFBFAC8DDA4 FOREIGN KEY (form_id_fk_id) REFERENCES form (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE field DROP FOREIGN KEY FK_5BF5455883BA3DC4');
        $this->addSql('ALTER TABLE response DROP FOREIGN KEY FK_3E7B0BFBFAC8DDA4');
        $this->addSql('DROP TABLE field');
        $this->addSql('DROP TABLE form');
        $this->addSql('DROP TABLE response');
    }
}
