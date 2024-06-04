<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240528085628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employee2skills (employee_entity_id INT NOT NULL, skill_entity_id INT NOT NULL, INDEX IDX_1661FFC084B5151F (employee_entity_id), INDEX IDX_1661FFC0D2D44600 (skill_entity_id), PRIMARY KEY(employee_entity_id, skill_entity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employee2skills ADD CONSTRAINT FK_1661FFC084B5151F FOREIGN KEY (employee_entity_id) REFERENCES employee_entity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employee2skills ADD CONSTRAINT FK_1661FFC0D2D44600 FOREIGN KEY (skill_entity_id) REFERENCES skill_entity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employee2new_skills DROP FOREIGN KEY FK_2E3C45B184B5151F');
        $this->addSql('ALTER TABLE employee2new_skills DROP FOREIGN KEY FK_2E3C45B1D2D44600');
        $this->addSql('ALTER TABLE employee2pre_skills DROP FOREIGN KEY FK_E164ED0484B5151F');
        $this->addSql('ALTER TABLE employee2pre_skills DROP FOREIGN KEY FK_E164ED04D2D44600');
        $this->addSql('DROP TABLE employee2new_skills');
        $this->addSql('DROP TABLE employee2pre_skills');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employee2new_skills (employee_entity_id INT NOT NULL, skill_entity_id INT NOT NULL, INDEX IDX_2E3C45B184B5151F (employee_entity_id), INDEX IDX_2E3C45B1D2D44600 (skill_entity_id), PRIMARY KEY(employee_entity_id, skill_entity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE employee2pre_skills (employee_entity_id INT NOT NULL, skill_entity_id INT NOT NULL, INDEX IDX_E164ED0484B5151F (employee_entity_id), INDEX IDX_E164ED04D2D44600 (skill_entity_id), PRIMARY KEY(employee_entity_id, skill_entity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE employee2new_skills ADD CONSTRAINT FK_2E3C45B184B5151F FOREIGN KEY (employee_entity_id) REFERENCES employee_entity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employee2new_skills ADD CONSTRAINT FK_2E3C45B1D2D44600 FOREIGN KEY (skill_entity_id) REFERENCES skill_entity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employee2pre_skills ADD CONSTRAINT FK_E164ED0484B5151F FOREIGN KEY (employee_entity_id) REFERENCES employee_entity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employee2pre_skills ADD CONSTRAINT FK_E164ED04D2D44600 FOREIGN KEY (skill_entity_id) REFERENCES skill_entity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employee2skills DROP FOREIGN KEY FK_1661FFC084B5151F');
        $this->addSql('ALTER TABLE employee2skills DROP FOREIGN KEY FK_1661FFC0D2D44600');
        $this->addSql('DROP TABLE employee2skills');
    }
}
