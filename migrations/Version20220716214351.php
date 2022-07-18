<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220716214351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, developer_id INT DEFAULT NULL, mentor_id INT DEFAULT NULL, message LONGTEXT DEFAULT NULL, updated_at DATETIME DEFAULT NULL, is_read TINYINT(1) DEFAULT NULL, INDEX IDX_B6F7494E64DD9267 (developer_id), INDEX IDX_B6F7494EDB403044 (mentor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_response (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, recipient_id INT DEFAULT NULL, question_id INT DEFAULT NULL, message LONGTEXT DEFAULT NULL, updated_at DATETIME DEFAULT NULL, is_read TINYINT(1) DEFAULT NULL, INDEX IDX_5D73BBF7F675F31B (author_id), INDEX IDX_5D73BBF7E92F8F78 (recipient_id), INDEX IDX_5D73BBF71E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stack (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, is_available TINYINT(1) DEFAULT NULL, profile_image VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_stack (user_id INT NOT NULL, stack_id INT NOT NULL, INDEX IDX_A36A8032A76ED395 (user_id), INDEX IDX_A36A803237C70060 (stack_id), PRIMARY KEY(user_id, stack_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E64DD9267 FOREIGN KEY (developer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EDB403044 FOREIGN KEY (mentor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE question_response ADD CONSTRAINT FK_5D73BBF7F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE question_response ADD CONSTRAINT FK_5D73BBF7E92F8F78 FOREIGN KEY (recipient_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE question_response ADD CONSTRAINT FK_5D73BBF71E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE user_stack ADD CONSTRAINT FK_A36A8032A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_stack ADD CONSTRAINT FK_A36A803237C70060 FOREIGN KEY (stack_id) REFERENCES stack (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question_response DROP FOREIGN KEY FK_5D73BBF71E27F6BF');
        $this->addSql('ALTER TABLE user_stack DROP FOREIGN KEY FK_A36A803237C70060');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E64DD9267');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EDB403044');
        $this->addSql('ALTER TABLE question_response DROP FOREIGN KEY FK_5D73BBF7F675F31B');
        $this->addSql('ALTER TABLE question_response DROP FOREIGN KEY FK_5D73BBF7E92F8F78');
        $this->addSql('ALTER TABLE user_stack DROP FOREIGN KEY FK_A36A8032A76ED395');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE question_response');
        $this->addSql('DROP TABLE stack');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_stack');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
