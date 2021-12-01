<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201216100954 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE error DROP FOREIGN KEY FK_5DDDBC71CFBF73EB');
        $this->addSql('DROP INDEX IDX_5DDDBC71CFBF73EB ON error');
        $this->addSql('ALTER TABLE error DROP make_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE error ADD make_id INT NOT NULL');
        $this->addSql('ALTER TABLE error ADD CONSTRAINT FK_5DDDBC71CFBF73EB FOREIGN KEY (make_id) REFERENCES make (id)');
        $this->addSql('CREATE INDEX IDX_5DDDBC71CFBF73EB ON error (make_id)');
    }
}
