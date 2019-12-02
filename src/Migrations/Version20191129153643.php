<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191129153643 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, logo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE color (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, hexa VARCHAR(7) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_6A2CA10C4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, brand_id INT NOT NULL, category_id INT NOT NULL, color_id INT NOT NULL, name VARCHAR(255) NOT NULL, reference VARCHAR(100) NOT NULL, gencode VARCHAR(14) DEFAULT NULL, description LONGTEXT DEFAULT NULL, public_price DOUBLE PRECISION NOT NULL, price DOUBLE PRECISION NOT NULL, stock INT NOT NULL, dimensions VARCHAR(100) DEFAULT NULL, weight VARCHAR(100) DEFAULT NULL, memory VARCHAR(100) DEFAULT NULL, os VARCHAR(100) DEFAULT NULL, display VARCHAR(100) DEFAULT NULL, battery VARCHAR(100) DEFAULT NULL, camera VARCHAR(100) DEFAULT NULL, sensors VARCHAR(100) DEFAULT NULL, connectivity VARCHAR(100) DEFAULT NULL, band_quad VARCHAR(100) DEFAULT NULL, INDEX IDX_D34A04AD44F5D008 (brand_id), INDEX IDX_D34A04AD12469DE2 (category_id), INDEX IDX_D34A04AD7ADA1FB5 (color_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD44F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD7ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD44F5D008');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD7ADA1FB5');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C4584665A');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE color');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE product');
    }
}
