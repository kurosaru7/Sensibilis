<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201124091803 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE make (id INT AUTO_INCREMENT NOT NULL, made TINYINT(1) NOT NULL, score INT NOT NULL, done_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE make_user (make_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_907805C1CFBF73EB (make_id), INDEX IDX_907805C1A76ED395 (user_id), PRIMARY KEY(make_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE make_theme (make_id INT NOT NULL, theme_id INT NOT NULL, INDEX IDX_740B07C9CFBF73EB (make_id), INDEX IDX_740B07C959027487 (theme_id), PRIMARY KEY(make_id, theme_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE make_user ADD CONSTRAINT FK_907805C1CFBF73EB FOREIGN KEY (make_id) REFERENCES make (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE make_user ADD CONSTRAINT FK_907805C1A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE make_theme ADD CONSTRAINT FK_740B07C9CFBF73EB FOREIGN KEY (make_id) REFERENCES make (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE make_theme ADD CONSTRAINT FK_740B07C959027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE make_user DROP FOREIGN KEY FK_907805C1CFBF73EB');
        $this->addSql('ALTER TABLE make_theme DROP FOREIGN KEY FK_740B07C9CFBF73EB');
        $this->addSql('DROP TABLE make');
        $this->addSql('DROP TABLE make_user');
        $this->addSql('DROP TABLE make_theme');
    }
}
