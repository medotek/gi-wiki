<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210325172144 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE community_build DROP FOREIGN KEY FK_C6D0889F5308DFC8');
        $this->addSql('DROP INDEX IDX_C6D0889F5308DFC8 ON community_build');
        $this->addSql('ALTER TABLE community_build CHANGE votes_id votes_build_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE community_build ADD CONSTRAINT FK_C6D0889F2E2C5E70 FOREIGN KEY (votes_build_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C6D0889F2E2C5E70 ON community_build (votes_build_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE community_build DROP FOREIGN KEY FK_C6D0889F2E2C5E70');
        $this->addSql('DROP INDEX IDX_C6D0889F2E2C5E70 ON community_build');
        $this->addSql('ALTER TABLE community_build CHANGE votes_build_id votes_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE community_build ADD CONSTRAINT FK_C6D0889F5308DFC8 FOREIGN KEY (votes_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C6D0889F5308DFC8 ON community_build (votes_id)');
    }
}
