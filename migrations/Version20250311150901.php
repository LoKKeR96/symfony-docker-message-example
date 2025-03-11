<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250311150901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE message_id_seq CASCADE');
        $this->addSql('ALTER TABLE message ALTER id DROP DEFAULT');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B6BD307FD17F50A6 ON message (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('DROP INDEX UNIQ_B6BD307FD17F50A6');
        $this->addSql('CREATE SEQUENCE message_id_seq');
        $this->addSql('SELECT setval(\'message_id_seq\', (SELECT MAX(id) FROM message))');
        $this->addSql('ALTER TABLE message ALTER id SET DEFAULT nextval(\'message_id_seq\')');
    }
}
