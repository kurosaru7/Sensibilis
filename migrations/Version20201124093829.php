<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201124093829 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE make ADD user_id INT DEFAULT NULL, ADD theme_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE make ADD CONSTRAINT FK_1ACC766EA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE make ADD CONSTRAINT FK_1ACC766E59027487 FOREIGN KEY (theme_id) REFERENCES theme (id)');
        $this->addSql('CREATE INDEX IDX_1ACC766EA76ED395 ON make (user_id)');
        $this->addSql('CREATE INDEX IDX_1ACC766E59027487 ON make (theme_id)');
        $this->addSql('ALTER TABLE theme DROP FOREIGN KEY FK_9775E708CFBF73EB');
        $this->addSql('DROP INDEX IDX_9775E708CFBF73EB ON theme');
        $this->addSql('ALTER TABLE theme DROP make_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CFBF73EB');
        $this->addSql('DROP INDEX IDX_8D93D649CFBF73EB ON user');
        $this->addSql('ALTER TABLE user DROP make_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE make DROP FOREIGN KEY FK_1ACC766EA76ED395');
        $this->addSql('ALTER TABLE make DROP FOREIGN KEY FK_1ACC766E59027487');
        $this->addSql('DROP INDEX IDX_1ACC766EA76ED395 ON make');
        $this->addSql('DROP INDEX IDX_1ACC766E59027487 ON make');
        $this->addSql('ALTER TABLE make DROP user_id, DROP theme_id');
        $this->addSql('ALTER TABLE theme ADD make_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE theme ADD CONSTRAINT FK_9775E708CFBF73EB FOREIGN KEY (make_id) REFERENCES make (id)');
        $this->addSql('CREATE INDEX IDX_9775E708CFBF73EB ON theme (make_id)');
        $this->addSql('ALTER TABLE `user` ADD make_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649CFBF73EB FOREIGN KEY (make_id) REFERENCES make (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649CFBF73EB ON `user` (make_id)');
    }
}
