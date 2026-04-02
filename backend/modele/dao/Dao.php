<?php
    interface Dao{

        public function findAll();
        public function findById($id);
        public function insert($donnee);
        public function update($donnee);
        public function delete($id);
        
    }
?>