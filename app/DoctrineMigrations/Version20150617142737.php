<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150617142737 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("ALTER TABLE Geometry ADD formattedZ VARCHAR(10) DEFAULT NULL, CHANGE angle angle VARCHAR(10) DEFAULT 'N/A'");
        $this->addSql("UPDATE Geometry SET angle = CASE angle WHEN 60 THEN 'Narrow' WHEN 360 THEN 'FullSky' ELSE 'N/A' END, formattedZ = FORMAT(Z,2)");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Geometry DROP formattedZ, CHANGE angle angle DOUBLE PRECISION DEFAULT NULL');
    }
}
