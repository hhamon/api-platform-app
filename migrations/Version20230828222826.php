<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230828222826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add parcel pickup and deposit table schemas.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE locker_facility (id INT UNSIGNED AUTO_INCREMENT NOT NULL, commissioned_at DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', name VARCHAR(20) NOT NULL, UNIQUE INDEX locker_facility_name_unique (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parcel_locker (id INT UNSIGNED AUTO_INCREMENT NOT NULL, facility_id INT UNSIGNED NOT NULL, serial VARCHAR(4) NOT NULL, size VARCHAR(2) NOT NULL, state VARCHAR(15) NOT NULL, unlock_code VARCHAR(6) DEFAULT NULL, INDEX IDX_71007A02A7014910 (facility_id), INDEX parcel_locker_search_available_at_facility_idx (facility_id, state, size), UNIQUE INDEX parcel_locker_facility_serial_unique (facility_id, serial), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parcel_unit (id INT UNSIGNED AUTO_INCREMENT NOT NULL, serial VARCHAR(10) NOT NULL, size VARCHAR(2) NOT NULL, customer_email VARCHAR(255) NOT NULL, is_damaged TINYINT(1) NOT NULL, INDEX parcel_unit_size_idx (size), UNIQUE INDEX parcel_unit_serial_unique (serial), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parcel_unit_deposit (id INT UNSIGNED AUTO_INCREMENT NOT NULL, parcel_id INT UNSIGNED NOT NULL, locker_id INT UNSIGNED NOT NULL, guid VARCHAR(36) NOT NULL, deposited_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_49722395465E670C (parcel_id), INDEX IDX_49722395841CF1E0 (locker_id), UNIQUE INDEX parcel_unit_deposit_guid_unique (guid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parcel_unit_pickup (id INT UNSIGNED AUTO_INCREMENT NOT NULL, parcel_id INT UNSIGNED NOT NULL, locker_id INT UNSIGNED NOT NULL, customer_id INT UNSIGNED NOT NULL, guid VARCHAR(36) NOT NULL, picked_up_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', unlock_code VARCHAR(6) NOT NULL, INDEX IDX_3E2D5AD3465E670C (parcel_id), INDEX IDX_3E2D5AD3841CF1E0 (locker_id), INDEX IDX_3E2D5AD39395C3F3 (customer_id), UNIQUE INDEX parcel_unit_pickup_guid_unique (guid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT UNSIGNED AUTO_INCREMENT NOT NULL, guid VARCHAR(36) NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX user_guid_unique (guid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE parcel_locker ADD CONSTRAINT FK_71007A02A7014910 FOREIGN KEY (facility_id) REFERENCES locker_facility (id)');
        $this->addSql('ALTER TABLE parcel_unit_deposit ADD CONSTRAINT FK_49722395465E670C FOREIGN KEY (parcel_id) REFERENCES parcel_unit (id)');
        $this->addSql('ALTER TABLE parcel_unit_deposit ADD CONSTRAINT FK_49722395841CF1E0 FOREIGN KEY (locker_id) REFERENCES parcel_locker (id)');
        $this->addSql('ALTER TABLE parcel_unit_pickup ADD CONSTRAINT FK_3E2D5AD3465E670C FOREIGN KEY (parcel_id) REFERENCES parcel_unit (id)');
        $this->addSql('ALTER TABLE parcel_unit_pickup ADD CONSTRAINT FK_3E2D5AD3841CF1E0 FOREIGN KEY (locker_id) REFERENCES parcel_locker (id)');
        $this->addSql('ALTER TABLE parcel_unit_pickup ADD CONSTRAINT FK_3E2D5AD39395C3F3 FOREIGN KEY (customer_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE parcel_locker DROP FOREIGN KEY FK_71007A02A7014910');
        $this->addSql('ALTER TABLE parcel_unit_deposit DROP FOREIGN KEY FK_49722395465E670C');
        $this->addSql('ALTER TABLE parcel_unit_deposit DROP FOREIGN KEY FK_49722395841CF1E0');
        $this->addSql('ALTER TABLE parcel_unit_pickup DROP FOREIGN KEY FK_3E2D5AD3465E670C');
        $this->addSql('ALTER TABLE parcel_unit_pickup DROP FOREIGN KEY FK_3E2D5AD3841CF1E0');
        $this->addSql('ALTER TABLE parcel_unit_pickup DROP FOREIGN KEY FK_3E2D5AD39395C3F3');
        $this->addSql('DROP TABLE locker_facility');
        $this->addSql('DROP TABLE parcel_locker');
        $this->addSql('DROP TABLE parcel_unit');
        $this->addSql('DROP TABLE parcel_unit_deposit');
        $this->addSql('DROP TABLE parcel_unit_pickup');
        $this->addSql('DROP TABLE user');
    }
}
