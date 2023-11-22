<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231122193104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, timezone_id INT NOT NULL, alpha2 VARCHAR(255) NOT NULL, code INT NOT NULL, alpha3 VARCHAR(255) NOT NULL, name_en_gb VARCHAR(255) NOT NULL, name_fr_fr VARCHAR(255) NOT NULL, INDEX IDX_5373C9663FE997DE (timezone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE data_enum (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, category VARCHAR(50) DEFAULT NULL, value VARCHAR(255) DEFAULT NULL, dev_key INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, height DOUBLE PRECISION DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, size DOUBLE PRECISION DEFAULT NULL, category VARCHAR(255) DEFAULT NULL, alt VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', title VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ip_whitelist (id INT AUTO_INCREMENT NOT NULL, ip VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_item (id INT AUTO_INCREMENT NOT NULL, page_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, weight INT NOT NULL, INDEX IDX_D754D550C4663E4 (page_id), INDEX IDX_D754D550727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_item_menu (menu_item_id INT NOT NULL, menu_id INT NOT NULL, INDEX IDX_AC75195C9AB44FE0 (menu_item_id), INDEX IDX_AC75195CCCD7E912 (menu_id), PRIMARY KEY(menu_item_id, menu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, page_type_id INT NOT NULL, banner_id INT DEFAULT NULL, image_thumbnail_id INT DEFAULT NULL, website_id INT NOT NULL, name VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, template VARCHAR(255) DEFAULT NULL, content_primary LONGTEXT DEFAULT NULL, content_secondary LONGTEXT DEFAULT NULL, content_tertiary LONGTEXT DEFAULT NULL, content_quaternary LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, dev_code_route_name VARCHAR(255) DEFAULT NULL, cta_title VARCHAR(50) DEFAULT NULL, cta_text VARCHAR(100) DEFAULT NULL, cta_url VARCHAR(255) DEFAULT NULL, banner_title VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', dev_key INT DEFAULT NULL, category VARCHAR(255) DEFAULT NULL, meta_description LONGTEXT NOT NULL, canonical_url VARCHAR(255) DEFAULT NULL, INDEX IDX_140AB620727ACA70 (parent_id), INDEX IDX_140AB6203F2C6706 (page_type_id), INDEX IDX_140AB620684EC833 (banner_id), INDEX IDX_140AB620F73056FE (image_thumbnail_id), INDEX IDX_140AB62018F45C82 (website_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page_gallery (id INT AUTO_INCREMENT NOT NULL, image_id INT NOT NULL, page_id INT NOT NULL, title VARCHAR(100) DEFAULT NULL, sub_title VARCHAR(255) NOT NULL, description VARCHAR(150) DEFAULT NULL, image_alt VARCHAR(100) DEFAULT NULL, image_url LONGTEXT DEFAULT NULL, image_title VARCHAR(100) DEFAULT NULL, cta_text VARCHAR(50) DEFAULT NULL, cta_title VARCHAR(100) DEFAULT NULL, cta_url LONGTEXT DEFAULT NULL, weight INT NOT NULL, INDEX IDX_BD4B93AF3DA5256D (image_id), INDEX IDX_BD4B93AFC4663E4 (page_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, dev_key INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timezone (id INT AUTO_INCREMENT NOT NULL, country_code VARCHAR(2) NOT NULL, coordinates VARCHAR(15) NOT NULL, time_zone VARCHAR(32) NOT NULL, utc_offset VARCHAR(8) NOT NULL, utc_dst_offset VARCHAR(8) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_backend (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D7E8D77BE7927C74 (email), INDEX IDX_D7E8D77BF92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE website (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, domain VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, hostname VARCHAR(255) NOT NULL, protocol VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE country ADD CONSTRAINT FK_5373C9663FE997DE FOREIGN KEY (timezone_id) REFERENCES timezone (id)');
        $this->addSql('ALTER TABLE menu_item ADD CONSTRAINT FK_D754D550C4663E4 FOREIGN KEY (page_id) REFERENCES page (id)');
        $this->addSql('ALTER TABLE menu_item ADD CONSTRAINT FK_D754D550727ACA70 FOREIGN KEY (parent_id) REFERENCES menu_item (id)');
        $this->addSql('ALTER TABLE menu_item_menu ADD CONSTRAINT FK_AC75195C9AB44FE0 FOREIGN KEY (menu_item_id) REFERENCES menu_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_item_menu ADD CONSTRAINT FK_AC75195CCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620727ACA70 FOREIGN KEY (parent_id) REFERENCES page (id)');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB6203F2C6706 FOREIGN KEY (page_type_id) REFERENCES page_type (id)');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620684EC833 FOREIGN KEY (banner_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620F73056FE FOREIGN KEY (image_thumbnail_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB62018F45C82 FOREIGN KEY (website_id) REFERENCES website (id)');
        $this->addSql('ALTER TABLE page_gallery ADD CONSTRAINT FK_BD4B93AF3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE page_gallery ADD CONSTRAINT FK_BD4B93AFC4663E4 FOREIGN KEY (page_id) REFERENCES page (id)');
        $this->addSql('ALTER TABLE user_backend ADD CONSTRAINT FK_D7E8D77BF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE country DROP FOREIGN KEY FK_5373C9663FE997DE');
        $this->addSql('ALTER TABLE menu_item DROP FOREIGN KEY FK_D754D550C4663E4');
        $this->addSql('ALTER TABLE menu_item DROP FOREIGN KEY FK_D754D550727ACA70');
        $this->addSql('ALTER TABLE menu_item_menu DROP FOREIGN KEY FK_AC75195C9AB44FE0');
        $this->addSql('ALTER TABLE menu_item_menu DROP FOREIGN KEY FK_AC75195CCCD7E912');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB620727ACA70');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB6203F2C6706');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB620684EC833');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB620F73056FE');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB62018F45C82');
        $this->addSql('ALTER TABLE page_gallery DROP FOREIGN KEY FK_BD4B93AF3DA5256D');
        $this->addSql('ALTER TABLE page_gallery DROP FOREIGN KEY FK_BD4B93AFC4663E4');
        $this->addSql('ALTER TABLE user_backend DROP FOREIGN KEY FK_D7E8D77BF92F3E70');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE data_enum');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE ip_whitelist');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE menu_item');
        $this->addSql('DROP TABLE menu_item_menu');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE page_gallery');
        $this->addSql('DROP TABLE page_type');
        $this->addSql('DROP TABLE timezone');
        $this->addSql('DROP TABLE user_backend');
        $this->addSql('DROP TABLE website');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
