<?php
class EcoUserType {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllUserTypes() {
        $sql = "SELECT * FROM ecousertypes ORDER BY name";
        return $this->db->findAll($sql);
    }

    public function getUserTypeById($id) {
        $sql = "SELECT * FROM ecousertypes WHERE id = :id";
        return $this->db->findOne($sql, [':id' => $id]);
    }
}