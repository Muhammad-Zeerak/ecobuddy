<?php
class EcoCategory {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllCategories() {
        $sql = "SELECT * FROM ecocategories ORDER BY id";
        return $this->db->findAll($sql);
    }

    public function getCategoryById($id) {
        $sql = "SELECT * FROM ecocategories WHERE id = :id";
        return $this->db->findOne($sql, [':id' => $id]);
    }

    public function createCategory($name) {
        $sql = "INSERT INTO ecocategories (name) VALUES (:name)";
        return $this->db->insert($sql, [':name' => $name]);
    }

    public function updateCategory($id, $name) {
        $sql = "UPDATE ecocategories SET name = :name WHERE id = :id";
        return $this->db->update($sql, [':id' => $id, ':name' => $name]);
    }

    public function deleteCategory($id) {
        $sql = "DELETE FROM ecocategories WHERE id = :id";
        return $this->db->delete($sql, [':id' => $id]);
    }
}