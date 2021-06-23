<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210623101252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alimentation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaires (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, recette_id_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', edited_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D9BEC0C49D86650F (user_id_id), INDEX IDX_D9BEC0C483B016C1 (recette_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cuisson (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etapes (id INT AUTO_INCREMENT NOT NULL, recette_id_id INT NOT NULL, is_number INT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, description LONGTEXT NOT NULL, INDEX IDX_E3443E1783B016C1 (recette_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredients (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredients_recette (id INT AUTO_INCREMENT NOT NULL, ingredients_id_id INT NOT NULL, unitemesure_id_id INT NOT NULL, recette_id_id INT NOT NULL, quantite INT NOT NULL, INDEX IDX_2B30A3D4111BE162 (ingredients_id_id), INDEX IDX_2B30A3D4D71466AB (unitemesure_id_id), INDEX IDX_2B30A3D483B016C1 (recette_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notes (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, recette_id_id INT NOT NULL, note INT NOT NULL, INDEX IDX_11BA68C9D86650F (user_id_id), INDEX IDX_11BA68C83B016C1 (recette_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plats (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recette (id INT AUTO_INCREMENT NOT NULL, author_id_id INT NOT NULL, cuisson_id_id INT NOT NULL, alimentation_id_id INT NOT NULL, plats_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, temps_preparation INT DEFAULT NULL, temps_cuisson INT DEFAULT NULL, nb_personnes INT DEFAULT NULL, difficulte INT NOT NULL, INDEX IDX_49BB639069CCBE9A (author_id_id), INDEX IDX_49BB639069913907 (cuisson_id_id), INDEX IDX_49BB639096BA16 (alimentation_id_id), INDEX IDX_49BB6390A44214B2 (plats_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recette_user (recette_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_C0933C1289312FE9 (recette_id), INDEX IDX_C0933C12A76ED395 (user_id), PRIMARY KEY(recette_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unite_mesure (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C49D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C483B016C1 FOREIGN KEY (recette_id_id) REFERENCES recette (id)');
        $this->addSql('ALTER TABLE etapes ADD CONSTRAINT FK_E3443E1783B016C1 FOREIGN KEY (recette_id_id) REFERENCES recette (id)');
        $this->addSql('ALTER TABLE ingredients_recette ADD CONSTRAINT FK_2B30A3D4111BE162 FOREIGN KEY (ingredients_id_id) REFERENCES ingredients (id)');
        $this->addSql('ALTER TABLE ingredients_recette ADD CONSTRAINT FK_2B30A3D4D71466AB FOREIGN KEY (unitemesure_id_id) REFERENCES unite_mesure (id)');
        $this->addSql('ALTER TABLE ingredients_recette ADD CONSTRAINT FK_2B30A3D483B016C1 FOREIGN KEY (recette_id_id) REFERENCES recette (id)');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68C9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68C83B016C1 FOREIGN KEY (recette_id_id) REFERENCES recette (id)');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB639069CCBE9A FOREIGN KEY (author_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB639069913907 FOREIGN KEY (cuisson_id_id) REFERENCES cuisson (id)');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB639096BA16 FOREIGN KEY (alimentation_id_id) REFERENCES alimentation (id)');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390A44214B2 FOREIGN KEY (plats_id_id) REFERENCES plats (id)');
        $this->addSql('ALTER TABLE recette_user ADD CONSTRAINT FK_C0933C1289312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette_user ADD CONSTRAINT FK_C0933C12A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB639096BA16');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB639069913907');
        $this->addSql('ALTER TABLE ingredients_recette DROP FOREIGN KEY FK_2B30A3D4111BE162');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390A44214B2');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C483B016C1');
        $this->addSql('ALTER TABLE etapes DROP FOREIGN KEY FK_E3443E1783B016C1');
        $this->addSql('ALTER TABLE ingredients_recette DROP FOREIGN KEY FK_2B30A3D483B016C1');
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68C83B016C1');
        $this->addSql('ALTER TABLE recette_user DROP FOREIGN KEY FK_C0933C1289312FE9');
        $this->addSql('ALTER TABLE ingredients_recette DROP FOREIGN KEY FK_2B30A3D4D71466AB');
        $this->addSql('DROP TABLE alimentation');
        $this->addSql('DROP TABLE commentaires');
        $this->addSql('DROP TABLE cuisson');
        $this->addSql('DROP TABLE etapes');
        $this->addSql('DROP TABLE ingredients');
        $this->addSql('DROP TABLE ingredients_recette');
        $this->addSql('DROP TABLE notes');
        $this->addSql('DROP TABLE plats');
        $this->addSql('DROP TABLE recette');
        $this->addSql('DROP TABLE recette_user');
        $this->addSql('DROP TABLE unite_mesure');
    }
}
