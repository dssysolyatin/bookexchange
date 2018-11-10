<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181110094860 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql(
            'CREATE INDEX book_fulltext_english_title ON book USING gin(to_tsvector(\'english\', info->>\'title\'))'
        );

        $this->addSql(
            'CREATE INDEX book_fulltext_russian_title ON book USING gin(to_tsvector(\'russian\', info->>\'title\'))'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX book_fulltext_english_title');
        $this->addSql('DROP INDEX book_fulltext_russian_title');
    }
}
