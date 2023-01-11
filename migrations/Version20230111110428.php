<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230111110428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mark ADD lieu_id INT NOT NULL');
        $this->addSql('ALTER TABLE mark ADD CONSTRAINT FK_6674F2716AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id)');
        $this->addSql('CREATE INDEX IDX_6674F2716AB213CC ON mark (lieu_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mark DROP FOREIGN KEY FK_6674F2716AB213CC');
        $this->addSql('DROP INDEX IDX_6674F2716AB213CC ON mark');
        $this->addSql('ALTER TABLE mark DROP lieu_id');
    }
}
