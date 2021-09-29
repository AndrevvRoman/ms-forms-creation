<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210923021908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE field ADD input_type_fk_id INT NOT NULL, ADD response_type_fk_id INT NOT NULL');
        $this->addSql('ALTER TABLE field ADD CONSTRAINT FK_5BF545586E838C70 FOREIGN KEY (input_type_fk_id) REFERENCES input_type (id)');
        $this->addSql('ALTER TABLE field ADD CONSTRAINT FK_5BF54558788369C7 FOREIGN KEY (response_type_fk_id) REFERENCES response_type (id)');
        $this->addSql('CREATE INDEX IDX_5BF545586E838C70 ON field (input_type_fk_id)');
        $this->addSql('CREATE INDEX IDX_5BF54558788369C7 ON field (response_type_fk_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE field DROP FOREIGN KEY FK_5BF545586E838C70');
        $this->addSql('ALTER TABLE field DROP FOREIGN KEY FK_5BF54558788369C7');
        $this->addSql('DROP INDEX IDX_5BF545586E838C70 ON field');
        $this->addSql('DROP INDEX IDX_5BF54558788369C7 ON field');
        $this->addSql('ALTER TABLE field DROP input_type_fk_id, DROP response_type_fk_id');
    }
}
