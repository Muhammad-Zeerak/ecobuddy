<?php
class EcoFacilityStatus {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllStatuses() {
        $sql = "SELECT s.*, f.title as facility_title, u.username 
                FROM ecofacilitystatus s
                JOIN ecofacilities f ON s.facilityid = f.id
                JOIN ecouser u ON s.userId = u.id
                ORDER BY s.id DESC";

        return $this->db->findAll($sql);
    }

    public function getStatusById($id) {
        $sql = "SELECT s.*, f.title as facility_title, u.username 
                FROM ecofacilitystatus s
                JOIN ecofacilities f ON s.facilityid = f.id
                JOIN ecouser u ON s.userId = u.id
                WHERE s.id = :id";

        return $this->db->findOne($sql, [':id' => $id]);
    }

    public function createStatus($facilityId, $userId, $comment) {
        $sql = "INSERT INTO ecofacilitystatus (facilityid, userId, statuscomment) 
                VALUES (:facilityid, :userId, :comment)";

        return $this->db->insert($sql, [
            ':facilityid' => $facilityId,
            ':userId' => $userId,
            ':comment' => $comment
        ]);
    }

    public function updateStatus($id, $comment) {
        $sql = "UPDATE ecofacilitystatus SET statuscomment = :comment WHERE id = :id";
        return $this->db->update($sql, [':id' => $id, ':comment' => $comment]);
    }

    public function deleteStatus($id) {
        $sql = "DELETE FROM ecofacilitystatus WHERE id = :id";
        return $this->db->delete($sql, [':id' => $id]);
    }
}