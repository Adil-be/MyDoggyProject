<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230612211411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE breed (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, descritpion LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE breed_dog (breed_id INT NOT NULL, dog_id INT NOT NULL, INDEX IDX_7AEFF8DCA8B4A30F (breed_id), INDEX IDX_7AEFF8DC634DFEB (dog_id), PRIMARY KEY(breed_id, dog_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE breed_dog ADD CONSTRAINT FK_7AEFF8DCA8B4A30F FOREIGN KEY (breed_id) REFERENCES breed (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE breed_dog ADD CONSTRAINT FK_7AEFF8DC634DFEB FOREIGN KEY (dog_id) REFERENCES dog (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE race_dog DROP FOREIGN KEY FK_94277487634DFEB');
        $this->addSql('ALTER TABLE race_dog DROP FOREIGN KEY FK_942774876E59D40D');
        $this->addSql('DROP TABLE race_dog');
        $this->addSql('DROP TABLE race');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE race_dog (race_id INT NOT NULL, dog_id INT NOT NULL, INDEX IDX_942774876E59D40D (race_id), INDEX IDX_94277487634DFEB (dog_id), PRIMARY KEY(race_id, dog_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE race (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, descritpion LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE race_dog ADD CONSTRAINT FK_94277487634DFEB FOREIGN KEY (dog_id) REFERENCES dog (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE race_dog ADD CONSTRAINT FK_942774876E59D40D FOREIGN KEY (race_id) REFERENCES race (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE breed_dog DROP FOREIGN KEY FK_7AEFF8DCA8B4A30F');
        $this->addSql('ALTER TABLE breed_dog DROP FOREIGN KEY FK_7AEFF8DC634DFEB');
        $this->addSql('DROP TABLE breed');
        $this->addSql('DROP TABLE breed_dog');
    }
}
