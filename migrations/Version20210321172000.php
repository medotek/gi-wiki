<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210321172000 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Setup of the database';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE artifact (id INT AUTO_INCREMENT NOT NULL, set_name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE build (id INT AUTO_INCREMENT NOT NULL, game_character_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_BDA0F2DBFFE68A1B (game_character_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE build_weapon (build_id INT NOT NULL, weapon_id INT NOT NULL, INDEX IDX_FEA1A61917C13F8B (build_id), INDEX IDX_FEA1A61995B82273 (weapon_id), PRIMARY KEY(build_id, weapon_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `character` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, element VARCHAR(255) NOT NULL, rarity INT NOT NULL, weapon_type VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE community_build (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, build_id INT NOT NULL, tags LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', votes INT NOT NULL, creation_date DATETIME NOT NULL, INDEX IDX_C6D0889FF675F31B (author_id), UNIQUE INDEX UNIQ_C6D0889F17C13F8B (build_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE official_build (id INT AUTO_INCREMENT NOT NULL, build_id INT NOT NULL, UNIQUE INDEX UNIQ_550F971217C13F8B (build_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `set` (id INT AUTO_INCREMENT NOT NULL, build_id INT NOT NULL, stat VARCHAR(255) NOT NULL, INDEX IDX_E61425DC17C13F8B (build_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE set_artifact (set_id INT NOT NULL, artifact_id INT NOT NULL, INDEX IDX_760CBB1110FB0D18 (set_id), INDEX IDX_760CBB11E28B07AC (artifact_id), PRIMARY KEY(set_id, artifact_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, creation_date DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE weapon (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, second_stat VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE build ADD CONSTRAINT FK_BDA0F2DBFFE68A1B FOREIGN KEY (game_character_id) REFERENCES `character` (id)');
        $this->addSql('ALTER TABLE build_weapon ADD CONSTRAINT FK_FEA1A61917C13F8B FOREIGN KEY (build_id) REFERENCES build (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE build_weapon ADD CONSTRAINT FK_FEA1A61995B82273 FOREIGN KEY (weapon_id) REFERENCES weapon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE community_build ADD CONSTRAINT FK_C6D0889FF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE community_build ADD CONSTRAINT FK_C6D0889F17C13F8B FOREIGN KEY (build_id) REFERENCES build (id)');
        $this->addSql('ALTER TABLE official_build ADD CONSTRAINT FK_550F971217C13F8B FOREIGN KEY (build_id) REFERENCES build (id)');
        $this->addSql('ALTER TABLE `set` ADD CONSTRAINT FK_E61425DC17C13F8B FOREIGN KEY (build_id) REFERENCES build (id)');
        $this->addSql('ALTER TABLE set_artifact ADD CONSTRAINT FK_760CBB1110FB0D18 FOREIGN KEY (set_id) REFERENCES `set` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE set_artifact ADD CONSTRAINT FK_760CBB11E28B07AC FOREIGN KEY (artifact_id) REFERENCES artifact (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE set_artifact DROP FOREIGN KEY FK_760CBB11E28B07AC');
        $this->addSql('ALTER TABLE build_weapon DROP FOREIGN KEY FK_FEA1A61917C13F8B');
        $this->addSql('ALTER TABLE community_build DROP FOREIGN KEY FK_C6D0889F17C13F8B');
        $this->addSql('ALTER TABLE official_build DROP FOREIGN KEY FK_550F971217C13F8B');
        $this->addSql('ALTER TABLE `set` DROP FOREIGN KEY FK_E61425DC17C13F8B');
        $this->addSql('ALTER TABLE build DROP FOREIGN KEY FK_BDA0F2DBFFE68A1B');
        $this->addSql('ALTER TABLE set_artifact DROP FOREIGN KEY FK_760CBB1110FB0D18');
        $this->addSql('ALTER TABLE community_build DROP FOREIGN KEY FK_C6D0889FF675F31B');
        $this->addSql('ALTER TABLE build_weapon DROP FOREIGN KEY FK_FEA1A61995B82273');
        $this->addSql('DROP TABLE artifact');
        $this->addSql('DROP TABLE build');
        $this->addSql('DROP TABLE build_weapon');
        $this->addSql('DROP TABLE `character`');
        $this->addSql('DROP TABLE community_build');
        $this->addSql('DROP TABLE official_build');
        $this->addSql('DROP TABLE `set`');
        $this->addSql('DROP TABLE set_artifact');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE weapon');
    }
}
