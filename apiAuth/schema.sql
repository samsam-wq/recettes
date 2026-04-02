create database Recettes_Utilisateurs;

use Recettes_Utilisateurs;

drop table if exists utilisateur;

create table utilisateur (
                        utilisateur_id int not null auto_increment,
                        login varchar(50),
                        password varchar(50),
                        groupe TINYINT UNSIGNED,
                        constraint pk_utilisateur primary key (utilisateur_id)
);

insert into utilisateur (login ,password ,role) 
values("Anna","!Anna",1);
insert into utilisateur (login ,password ,role) 
values("Sam","!Sam",1);

commit;