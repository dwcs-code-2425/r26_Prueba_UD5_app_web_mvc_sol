<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260311100924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE autor (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) DEFAULT NULL, fecha_nacimiento DATE DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE editorial (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE libro (id INT AUTO_INCREMENT NOT NULL, titulo VARCHAR(200) DEFAULT NULL, fecha_publicacion DATETIME DEFAULT NULL, unidades_vendidas INT DEFAULT NULL, editorial_id INT DEFAULT NULL, INDEX IDX_5799AD2BBAF1A24D (editorial_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE libro_autor (libro_id INT NOT NULL, autor_id INT NOT NULL, INDEX IDX_F7588AEFC0238522 (libro_id), INDEX IDX_F7588AEF14D45BBE (autor_id), PRIMARY KEY (libro_id, autor_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE nota (id INT AUTO_INCREMENT NOT NULL, titulo VARCHAR(50) NOT NULL, descripcion VARCHAR(255) NOT NULL, fecha_modificacion DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE libro ADD CONSTRAINT FK_5799AD2BBAF1A24D FOREIGN KEY (editorial_id) REFERENCES editorial (id)');
        $this->addSql('ALTER TABLE libro_autor ADD CONSTRAINT FK_F7588AEFC0238522 FOREIGN KEY (libro_id) REFERENCES libro (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE libro_autor ADD CONSTRAINT FK_F7588AEF14D45BBE FOREIGN KEY (autor_id) REFERENCES autor (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE libro DROP FOREIGN KEY FK_5799AD2BBAF1A24D');
        $this->addSql('ALTER TABLE libro_autor DROP FOREIGN KEY FK_F7588AEFC0238522');
        $this->addSql('ALTER TABLE libro_autor DROP FOREIGN KEY FK_F7588AEF14D45BBE');
        $this->addSql('DROP TABLE autor');
        $this->addSql('DROP TABLE editorial');
        $this->addSql('DROP TABLE libro');
        $this->addSql('DROP TABLE libro_autor');
        $this->addSql('DROP TABLE nota');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
