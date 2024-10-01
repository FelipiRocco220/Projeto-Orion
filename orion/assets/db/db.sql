drop database if exists orion;
create database orion;
use orion;

/* ------------------ Cargos ------------------ */
create table roles(
	role_id int primary key not null,
	name varchar(30) not null
);
insert into roles(role_id, name) values (0, 'user'), (1, 'mod'), (2, 'admin'), (3, 'owner');



/* ------------------ Tags ------------------ */
create table tags(
	id int auto_increment primary key,
    name varchar(30) not null
);
insert into tags(name) values ('aventura'), ('fantasia'), ('comédia'), ('romance'), ('suspense'), ('ficção'), ('cyberpunk'), ('apocalipse'), ('yuri'), ('yaoi'), ('ação'), ('luta'), ('sci-fi'), ('contos'), ('+18');



/* ------------------ Tabelas Usuario ------------------ */
create table users(
	id int auto_increment primary key,
	username varchar(60) not null,
	tag varchar(50) not null,
    email varchar(100) not null,
	password varchar(255) not null,
    birth_date date not null,
    creation_date datetime not null default CURRENT_TIME,
    pfp varchar(100),
	role_id int not null,
	foreign key (role_id) references roles(role_id)
);



/* ------------------ Tabelas Novels ------------------ */
create table novels(
	id int auto_increment primary key,
    thumbnail varchar(100),
    title varchar(60) not null,
    description varchar(1200) not null,
    visibility int not null,
    creation_date datetime not null default CURRENT_TIME,
    post_date datetime not null default CURRENT_TIME,
    author_id int not null,
	foreign key (author_id) references users(id)
);
/* visibility: 0 Privado, 1 Público, 2 Não listado. */

create table novels_ratings(
	id int auto_increment primary key,
    rating float not null,
    user_id int not null,
    novel_id int not null,
    foreign key (user_id) references users(id),
    foreign key (novel_id) references novels(id)
);
/* insert into novels_ratings(rating, user_id, novel_id) values(3, 1, 3);
update novels_ratings set rating = 4 where user_id = 1; */

create table novels_favorites(
	id int auto_increment primary key,
    user_id int not null,
    novel_id int not null,
    foreign key (user_id) references users(id),
    foreign key (novel_id) references novels(id)
);

create table novels_volumes(
	id int auto_increment primary key,
    pos int not null,
    novel_id int not null,
    foreign key (novel_id) references novels(id)
);

create table novels_chapters(
	id int auto_increment primary key,
    pos int not null,
    file varchar(100) not null,
    title varchar(60) not null,
    visibility int not null,
    creation_date datetime not null default CURRENT_TIME,
    post_date datetime not null default CURRENT_TIME,
    volume_id int,
    novel_id int not null,
    foreign key (novel_id) references novels(id),
    foreign key (volume_id) references novels_volumes(id)
);

create table novels_tags(
	id int auto_increment primary key,
    novel_id int not null,
    tag_id int not null,
    foreign key (novel_id) references novels(id),
    foreign key (tag_id) references tags(id)
);



update users set role_id = 3 where tag = 'teste';
select name from novels_tags join novels on novels_tags.novel_id = novels.id join tags on tags.id = novels_tags.tag_id where novels.id = 2;
update novels_chapters set visibility = 1 where visibility != 1;

select * from novels;
select * from novels_tags where novel_id = 2;
select * from novels join novels_tags on novels_tags.novel_id = novels.id where novels_tags.tag_id = 2;

update users set role_id = 3 where tag = 'joao';
delete from novels where author_id = 1;
delete from users where tag = 'felipi';
select * from users;
/*
show tables;
select * from users;
select * from novels;
insert into novels_ratings(rating, user_id, novel_id) values(3.5, 2, 2);
delete from novels_ratings;
insert into novels_tags(novel_id, tag_id) values (1, 1);
*/
/*
update users set role_id = 3 where tag = 'haone';
update users set pfp = null where tag = 'haone';
update novels set visibility = 2 where id = 1;
*/
/* selects */
/*
select id, username, tag, password, creation_date, roles.name as role from users join roles on roles.role_id = users.role_id;
select pfp from users where id = 1;

select title, name from novel_tags join tags on novel_tags.tag_id = tags.id join novels on novel_tags.novel_id = novels.id;
*/