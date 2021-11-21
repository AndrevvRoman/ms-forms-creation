<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211121085515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE field DROP FOREIGN KEY FK_5BF5455883BA3DC4');
        $this->addSql('DROP INDEX IDX_5BF5455883BA3DC4 ON field');
        $this->addSql('ALTER TABLE field CHANGE id_form_fk_id form_id INT NOT NULL');
        $this->addSql('ALTER TABLE field ADD CONSTRAINT FK_5BF545585FF69B7D FOREIGN KEY (form_id) REFERENCES form (id)');
        $this->addSql('CREATE INDEX IDX_5BF545585FF69B7D ON field (form_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE field DROP FOREIGN KEY FK_5BF545585FF69B7D');
        $this->addSql('DROP INDEX IDX_5BF545585FF69B7D ON field');
        $this->addSql('ALTER TABLE field CHANGE form_id id_form_fk_id INT NOT NULL');
        $this->addSql('ALTER TABLE field ADD CONSTRAINT FK_5BF5455883BA3DC4 FOREIGN KEY (id_form_fk_id) REFERENCES form (id)');
        $this->addSql('CREATE INDEX IDX_5BF5455883BA3DC4 ON field (id_form_fk_id)');
    }
}
