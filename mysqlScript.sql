create database todolist;
use todolist;

create table tasks(
	id int AUTO_INCREMENT NOT NULL UNIQUE,
    title varchar(100) NOT NULL,
    finished boolean DEFAULT false,
    finished_at timestamp
);

select * from tasks;