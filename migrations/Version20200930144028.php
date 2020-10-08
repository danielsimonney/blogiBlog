<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200930144028 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_blog_post (category_id INT NOT NULL, blog_post_id INT NOT NULL, INDEX IDX_7D3A322D12469DE2 (category_id), INDEX IDX_7D3A322DA77FBEAF (blog_post_id), PRIMARY KEY(category_id, blog_post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_blog_post ADD CONSTRAINT FK_7D3A322D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_blog_post ADD CONSTRAINT FK_7D3A322DA77FBEAF FOREIGN KEY (blog_post_id) REFERENCES blog_post (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_blog_post DROP FOREIGN KEY FK_7D3A322D12469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_blog_post');
    }
}
