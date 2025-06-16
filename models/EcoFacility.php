<?php
class EcoFacility {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllFacilities($page = 1, $limit = 10, $orderBy = 'id', $orderDir = 'ASC') {
        $offset = ($page - 1) * $limit;

        $sql = "SELECT f.*, c.name as category_name, u.username as contributor_name
                FROM ecofacilities f 
                LEFT JOIN ecocategories c ON f.category = c.id
                LEFT JOIN ecouser u ON f.contributor = u.id
                ORDER BY f.$orderBy $orderDir
                LIMIT :limit OFFSET :offset";

        return $this->db->findAll($sql, [':limit' => $limit, ':offset' => $offset]);
    }

    public function getTotalFacilities() {
        $sql = "SELECT COUNT(*) as count FROM ecofacilities";
        $result = $this->db->findOne($sql);
        return $result['count'];
    }

    public function getFacilityById($id) {
        $sql = "SELECT f.*, c.name as category_name, u.username as contributor_name
                FROM ecofacilities f 
                LEFT JOIN ecocategories c ON f.category = c.id
                LEFT JOIN ecouser u ON f.contributor = u.id
                WHERE f.id = :id";

        return $this->db->findOne($sql, [':id' => $id]);
    }

    public function getFacilityStatuses($facilityId) {
        $sql = "SELECT s.*, u.username 
                FROM ecofacilitystatus s
                JOIN ecouser u ON s.userId = u.id
                WHERE s.facilityid = :id
                ORDER BY s.id DESC";

        return $this->db->findAll($sql, [':id' => $facilityId]);
    }

    public function searchFacilities($searchTerm, $category = null, $page = 1, $limit = 10, $orderBy = 'id', $orderDir = 'ASC') {
        $offset = ($page - 1) * $limit;
        $params = [];

        $sql = "SELECT f.*, c.name as category_name, u.username as contributor_name
                FROM ecofacilities f 
                LEFT JOIN ecocategories c ON f.category = c.id
                LEFT JOIN ecouser u ON f.contributor = u.id
                WHERE 1=1";

        if (!empty($searchTerm)) {
            $sql .= " AND (f.title LIKE :search OR f.description LIKE :search 
                    OR f.postcode LIKE :search OR f.town LIKE :search 
                    OR f.streetname LIKE :search)";
            $params[':search'] = '%' . $searchTerm . '%';
        }

        if (!empty($category)) {
            $sql .= " AND f.category = :category";
            $params[':category'] = $category;
        }

        $sql .= " ORDER BY f.$orderBy $orderDir LIMIT :limit OFFSET :offset";
        $params[':limit'] = $limit;
        $params[':offset'] = $offset;

        return $this->db->findAll($sql, $params);
    }

    public function getTotalSearchResults($searchTerm, $category = null) {
        $params = [];

        $sql = "SELECT COUNT(*) as count 
                FROM ecofacilities f 
                WHERE 1=1";

        if (!empty($searchTerm)) {
            $sql .= " AND (f.title LIKE :search OR f.description LIKE :search 
                    OR f.postcode LIKE :search OR f.town LIKE :search 
                    OR f.streetname LIKE :search)";
            $params[':search'] = '%' . $searchTerm . '%';
        }

        if (!empty($category)) {
            $sql .= " AND f.category = :category";
            $params[':category'] = $category;
        }

        $result = $this->db->findOne($sql, $params);
        return $result['count'];
    }

    public function createFacility($data) {
        // check if photo exists
        $photo = isset($data['photo']) ? $data['photo'] : null;

        $sql = "INSERT INTO ecofacilities (
                title, category, description, housenumber, streetname, 
                county, town, postcode, lng, lat, photo, contributor
            ) VALUES (
                :title, :category, :description, :housenumber, :streetname, 
                :county, :town, :postcode, :lng, :lat, :photo, :contributor
            )";

        return $this->db->insert($sql, [
            ':title' => $data['title'],
            ':category' => $data['category'],
            ':description' => $data['description'],
            ':housenumber' => $data['housenumber'],
            ':streetname' => $data['streetname'],
            ':county' => $data['county'],
            ':town' => $data['town'],
            ':postcode' => $data['postcode'],
            ':lng' => $data['lng'],
            ':lat' => $data['lat'],
            ':photo' => $photo,
            ':contributor' => $data['contributor']
        ]);
    }

    public function updateFacility($id, $data) {
        $sql = "UPDATE ecofacilities SET 
                title = :title, 
                category = :category, 
                description = :description, 
                housenumber = :housenumber, 
                streetname = :streetname, 
                county = :county, 
                town = :town, 
                postcode = :postcode, 
                lng = :lng, 
                lat = :lat";

        $params = [
            ':id' => $id,
            ':title' => $data['title'],
            ':category' => $data['category'],
            ':description' => $data['description'],
            ':housenumber' => $data['housenumber'],
            ':streetname' => $data['streetname'],
            ':county' => $data['county'],
            ':town' => $data['town'],
            ':postcode' => $data['postcode'],
            ':lng' => $data['lng'],
            ':lat' => $data['lat']
        ];

        // Only update photo if a new one is provided
        if (isset($data['photo']) && !empty($data['photo'])) {
            $sql .= ", photo = :photo";
            $params[':photo'] = $data['photo'];
        }

        $sql .= " WHERE id = :id";

        return $this->db->update($sql, $params);
    }

    public function deleteFacility($id) {
        // First delete related status entries
        $sql1 = "DELETE FROM ecofacilitystatus WHERE facilityid = :id";
        $this->db->delete($sql1, [':id' => $id]);

        // Then delete the facility
        $sql2 = "DELETE FROM ecofacilities WHERE id = :id";
        return $this->db->delete($sql2, [':id' => $id]);
    }

    public function addStatus($facilityId, $userId, $comment) {
        $sql = "INSERT INTO ecofacilitystatus (facilityid, userId, statuscomment) 
                VALUES (:facilityid, :userId, :comment)";

        return $this->db->insert($sql, [
            ':facilityid' => $facilityId,
            ':userId' => $userId,
            ':comment' => $comment
        ]);
    }

    public function deleteStatus($statusId) {
        $sql = "DELETE FROM ecofacilitystatus WHERE id = :id";
        return $this->db->delete($sql, [':id' => $statusId]);
    }

    public function getStatusById($id) {
        $sql = "SELECT s.*, f.id as facility_id, f.title as facility_title, u.username 
            FROM ecofacilitystatus s
            JOIN ecofacilities f ON s.facilityid = f.id
            JOIN ecouser u ON s.userId = u.id
            WHERE s.id = :id";

        return $this->db->findOne($sql, [':id' => $id]);
    }

    public function updateStatus($statusId, $comment) {
        $sql = "UPDATE ecofacilitystatus SET statuscomment = :comment WHERE id = :id";
        return $this->db->update($sql, [
            ':id' => $statusId,
            ':comment' => $comment
        ]);
    }
}