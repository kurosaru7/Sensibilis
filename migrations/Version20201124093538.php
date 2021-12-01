<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201124093538 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE make (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE theme ADD make_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE theme ADD CONSTRAINT FK_9775E708CFBF73EB FOREIGN KEY (make_id) REFERENCES make (id)');
        $this->addSql('CREATE INDEX IDX_9775E708CFBF73EB ON theme (make_id)');
        $this->addSql('ALTER TABLE user ADD make_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CFBF73EB FOREIGN KEY (make_id) REFERENCES make (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649CFBF73EB ON user (make_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE theme DROP FOREIGN KEY FK_9775E708CFBF73EB');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649CFBF73EB');
        $this->addSql('DROP TABLE make');
        $this->addSql('DROP INDEX IDX_9775E708CFBF73EB ON theme');
        $this->addSql('ALTER TABLE theme DROP make_id');
        $this->addSql('DROP INDEX IDX_8D93D649CFBF73EB ON `user`');
        $this->addSql('ALTER TABLE `user` DROP make_id');
    }
}
