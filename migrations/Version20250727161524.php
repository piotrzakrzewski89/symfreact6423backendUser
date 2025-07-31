<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Domain\Enum\UserRoleEnum;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250727161524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, created_by_id INT NOT NULL, updated_by_id INT NOT NULL, uuid UUID NOT NULL, company_id UUID DEFAULT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) NOT NULL, last_login_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, last_failed_login_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_active BOOLEAN NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, password_changed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, employee_number VARCHAR(50) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649D17F50A6 ON "user" (uuid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE INDEX IDX_8D93D649B03A8386 ON "user" (created_by_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649896DBBDE ON "user" (updated_by_id)');
        $this->addSql('COMMENT ON COLUMN "user".uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "user".company_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "user".last_login_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".last_failed_login_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".password_changed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('INSERT INTO "user"
         (created_by_id,updated_by_id,"uuid",email,"password",first_name,last_name,is_active,employee_number,roles) VALUES
	    (1,1,\'dd2f7b38-bf2d-47c0-8cb6-1894b348df12\'::uuid,\'admin@example.com\',\'$2y$13$RhqWYnUyvtQPj8AdBmNvrukVnmCFKLlOXMVlSg46ya36i1kRkbDaW\',\'Admin\',\'Systemowy\',true,\'0001\',\''.json_encode([UserRoleEnum::USER->value, UserRoleEnum::ADMIN->value]).'\');
');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649B03A8386');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649896DBBDE');
        $this->addSql('DROP TABLE "user"');
    }
}
