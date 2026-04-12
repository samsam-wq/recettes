<?php
    namespace backend\modele\dao;

    interface Dao{

        public function findAll():array;
        public function findById(mixed $id):?object;
        public function insert(object $donnee):string|bool;
        public function update(object $donnee):bool;
        public function delete(mixed $id):bool;
        
    }
?>