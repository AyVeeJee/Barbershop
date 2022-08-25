<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220824114034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE booking_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "comments_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "employees_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE lost_password_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "services_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "users_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE booking (id INT NOT NULL, appointer_id INT DEFAULT NULL, service_id INT DEFAULT NULL, employee_id INT DEFAULT NULL, begin_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX appointer_booking_idx ON booking (appointer_id)');
        $this->addSql('CREATE INDEX service_booking__idx ON booking (service_id)');
        $this->addSql('CREATE INDEX employee_booking_idx ON booking (employee_id)');
        $this->addSql('CREATE TABLE "comments" (id INT NOT NULL, user_comment_id INT NOT NULL, employee_id INT NOT NULL, content VARCHAR(1024) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX user_comments_idx ON "comments" (user_comment_id)');
        $this->addSql('CREATE INDEX employee_comments_idx ON "comments" (employee_id)');
        $this->addSql('CREATE TABLE "employees" (id INT NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(15) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, work_days JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA82C300E7927C74 ON "employees" (email)');
        $this->addSql('CREATE TABLE employee_service (employee_id INT NOT NULL, service_id INT NOT NULL, PRIMARY KEY(employee_id, service_id))');
        $this->addSql('CREATE INDEX IDX_61D1CCDD8C03F15C ON employee_service (employee_id)');
        $this->addSql('CREATE INDEX IDX_61D1CCDDED5CA9E6 ON employee_service (service_id)');
        $this->addSql('CREATE TABLE lost_password (id INT NOT NULL, userpass_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, active BOOLEAN NOT NULL, old_password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A492D0557035A65B ON lost_password (userpass_id)');
        $this->addSql('CREATE INDEX token_lost_password_idx ON lost_password (token)');
        $this->addSql('CREATE TABLE "services" (id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(1024) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7332E1692B36786B ON "services" (title)');
        $this->addSql('CREATE TABLE "users" (id INT NOT NULL, first_name VARCHAR(60) DEFAULT NULL, last_name VARCHAR(60) DEFAULT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON "users" (email)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDECEE930E1 FOREIGN KEY (appointer_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEED5CA9E6 FOREIGN KEY (service_id) REFERENCES "services" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE8C03F15C FOREIGN KEY (employee_id) REFERENCES "employees" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "comments" ADD CONSTRAINT FK_5F9E962A5F0EBBFF FOREIGN KEY (user_comment_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "comments" ADD CONSTRAINT FK_5F9E962A8C03F15C FOREIGN KEY (employee_id) REFERENCES "employees" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE employee_service ADD CONSTRAINT FK_61D1CCDD8C03F15C FOREIGN KEY (employee_id) REFERENCES "employees" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE employee_service ADD CONSTRAINT FK_61D1CCDDED5CA9E6 FOREIGN KEY (service_id) REFERENCES "services" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lost_password ADD CONSTRAINT FK_A492D0557035A65B FOREIGN KEY (userpass_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDE8C03F15C');
        $this->addSql('ALTER TABLE "comments" DROP CONSTRAINT FK_5F9E962A8C03F15C');
        $this->addSql('ALTER TABLE employee_service DROP CONSTRAINT FK_61D1CCDD8C03F15C');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDEED5CA9E6');
        $this->addSql('ALTER TABLE employee_service DROP CONSTRAINT FK_61D1CCDDED5CA9E6');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDECEE930E1');
        $this->addSql('ALTER TABLE "comments" DROP CONSTRAINT FK_5F9E962A5F0EBBFF');
        $this->addSql('ALTER TABLE lost_password DROP CONSTRAINT FK_A492D0557035A65B');
        $this->addSql('DROP SEQUENCE booking_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "comments_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "employees_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE lost_password_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "services_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "users_id_seq" CASCADE');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE "comments"');
        $this->addSql('DROP TABLE "employees"');
        $this->addSql('DROP TABLE employee_service');
        $this->addSql('DROP TABLE lost_password');
        $this->addSql('DROP TABLE "services"');
        $this->addSql('DROP TABLE "users"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
