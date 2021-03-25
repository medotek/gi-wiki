<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210325154052 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE build_artifact (build_id INT NOT NULL, artifact_id INT NOT NULL, INDEX IDX_ACC63A5A17C13F8B (build_id), INDEX IDX_ACC63A5AE28B07AC (artifact_id), PRIMARY KEY(build_id, artifact_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE build_artifact ADD CONSTRAINT FK_ACC63A5A17C13F8B FOREIGN KEY (build_id) REFERENCES build (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE build_artifact ADD CONSTRAINT FK_ACC63A5AE28B07AC FOREIGN KEY (artifact_id) REFERENCES artifact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE community_build DROP FOREIGN KEY FK_C6D0889F17C13F8B');
        $this->addSql('ALTER TABLE community_build ADD CONSTRAINT FK_C6D0889F17C13F8B FOREIGN KEY (build_id) REFERENCES build (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `set` DROP FOREIGN KEY FK_E61425DC17C13F8B');
        $this->addSql('DROP INDEX IDX_E61425DC17C13F8B ON `set`');
        $this->addSql('ALTER TABLE `set` DROP build_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE build_artifact');
        $this->addSql('ALTER TABLE community_build DROP FOREIGN KEY FK_C6D0889F17C13F8B');
        $this->addSql('ALTER TABLE community_build ADD CONSTRAINT FK_C6D0889F17C13F8B FOREIGN KEY (build_id) REFERENCES build (id)');
        $this->addSql('ALTER TABLE `set` ADD build_id INT NOT NULL');
        $this->addSql('ALTER TABLE `set` ADD CONSTRAINT FK_E61425DC17C13F8B FOREIGN KEY (build_id) REFERENCES build (id)');
        $this->addSql('CREATE INDEX IDX_E61425DC17C13F8B ON `set` (build_id)');
    }
}
