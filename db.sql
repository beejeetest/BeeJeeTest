drop table if exists users;
create table users
(
    id         int     not null primary key auto_increment,
    username   varchar(50),
    password   varchar(50),
    created_at integer not null,
    updated_at integer not null
);

insert into users (username, password, created_at, updated_at)
values ('admin', md5('123'), 1589974384, 1589974384);

drop table if exists tasks;
create table tasks
(
    id         int          not null primary key auto_increment,
    username   varchar(50)  not null,
    email      varchar(50)  not null,
    text       text         not null,
    status     varchar(10),
    created_at integer      not null,
    updated_at integer      not null,
    updated_by varchar(100) null
) DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;
