<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210224151211 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `option` DROP FOREIGN KEY FK_5A8600B0C3658564');
        $this->addSql('DROP INDEX IDX_5A8600B0C3658564 ON `option`');
        $this->addSql('ALTER TABLE `option` DROP data_user_id');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EC3658564');
        $this->addSql('DROP INDEX IDX_B6F7494EC3658564 ON question');
        $this->addSql('ALTER TABLE question DROP data_user_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `option` ADD data_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `option` ADD CONSTRAINT FK_5A8600B0C3658564 FOREIGN KEY (data_user_id) REFERENCES data_user (id)');
        $this->addSql('CREATE INDEX IDX_5A8600B0C3658564 ON `option` (data_user_id)');
        $this->addSql('ALTER TABLE question ADD data_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EC3658564 FOREIGN KEY (data_user_id) REFERENCES data_user (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494EC3658564 ON question (data_user_id)');
    }
}
