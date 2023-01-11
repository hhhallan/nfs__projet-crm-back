<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230111130359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE devis (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, commercial_id INT DEFAULT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_modification DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8B27C52B19EB6921 (client_id), INDEX IDX_8B27C52B7854071C (commercial_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, commercial_id INT DEFAULT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_modification DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', stat VARCHAR(255) NOT NULL, INDEX IDX_FE86641019EB6921 (client_id), INDEX IDX_FE8664107854071C (commercial_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE historic (id INT AUTO_INCREMENT NOT NULL, source_id INT DEFAULT NULL, historic_type VARCHAR(255) NOT NULL, target_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_AD52EF56953C1C61 (source_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, code_product VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, plateforme VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_in_devis (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, devis_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_A62B1C24584665A (product_id), INDEX IDX_A62B1C241DEFADA (devis_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_in_facture (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, facture_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_265218D54584665A (product_id), INDEX IDX_265218D57F2DEE08 (facture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, commercial_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, validate TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D6497854071C (commercial_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B19EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B7854071C FOREIGN KEY (commercial_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641019EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664107854071C FOREIGN KEY (commercial_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE historic ADD CONSTRAINT FK_AD52EF56953C1C61 FOREIGN KEY (source_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product_in_devis ADD CONSTRAINT FK_A62B1C24584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_in_devis ADD CONSTRAINT FK_A62B1C241DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
        $this->addSql('ALTER TABLE product_in_facture ADD CONSTRAINT FK_265218D54584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_in_facture ADD CONSTRAINT FK_265218D57F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497854071C FOREIGN KEY (commercial_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B19EB6921');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B7854071C');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE86641019EB6921');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE8664107854071C');
        $this->addSql('ALTER TABLE historic DROP FOREIGN KEY FK_AD52EF56953C1C61');
        $this->addSql('ALTER TABLE product_in_devis DROP FOREIGN KEY FK_A62B1C24584665A');
        $this->addSql('ALTER TABLE product_in_devis DROP FOREIGN KEY FK_A62B1C241DEFADA');
        $this->addSql('ALTER TABLE product_in_facture DROP FOREIGN KEY FK_265218D54584665A');
        $this->addSql('ALTER TABLE product_in_facture DROP FOREIGN KEY FK_265218D57F2DEE08');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497854071C');
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE historic');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_in_devis');
        $this->addSql('DROP TABLE product_in_facture');
        $this->addSql('DROP TABLE user');
    }
}
