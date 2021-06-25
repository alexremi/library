<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201127063540 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql(<<<'SQL'
create table book
(
	id int  not null auto_increment
		primary key,
	name varchar(255) not null,
	description varchar(255) not null,
	year int not null,
	image varchar(1024) null
)
collate=utf8mb4_unicode_ci;

create table author
(
	id int not null auto_increment
		primary key,
	name varchar(255) not null
)
collate=utf8mb4_unicode_ci;

create table book_author
(
	book_id int not null,
	author_id int not null,
	primary key (book_id,author_id)
)
collate=utf8mb4_unicode_ci;
SQL
);
        // this up() migration is auto-generated, please modify it to your needs

    }

    public function down(Schema $schema) : void
    {
        $this->addSql(<<<'SQL'
drop table book_author;
drop table author;
drop table book;
SQL
        );
    }
}
