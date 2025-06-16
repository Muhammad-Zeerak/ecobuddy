<?php
class EcoUser {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function authenticate($username, $password) {
        $sql = "SELECT * FROM ecouser WHERE username = :username";
        $user = $this->db->findOne($sql, [':username' => $username]);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    public function getUserById($id) {
        $sql = "SELECT u.*, t.name as user_type_name 
                FROM ecouser u
                JOIN ecousertypes t ON u.usertype = t.id
                WHERE u.id = :id";
        return $this->db->findOne($sql, [':id' => $id]);
    }

    public function getAllUsers() {
        $sql = "SELECT u.*, t.name as user_type_name 
                FROM ecouser u
                JOIN ecousertypes t ON u.usertype = t.id
                ORDER BY u.username";
        return $this->db->findAll($sql);
    }

    public function getUserTypes() {
        $sql = "SELECT * FROM ecousertypes ORDER BY name";
        return $this->db->findAll($sql);
    }

    public function createUser($username, $password, $userType) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO ecouser (username, password, usertype) 
                VALUES (:username, :password, :usertype)";

        return $this->db->insert($sql, [
            ':username' => $username,
            ':password' => $hashedPassword,
            ':usertype' => $userType
        ]);
    }

    public function updateUser($id, $username, $userType, $password = null) {
        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE ecouser SET username = :username, password = :password, usertype = :usertype 
                    WHERE id = :id";
            $params = [
                ':id' => $id,
                ':username' => $username,
                ':password' => $hashedPassword,
                ':usertype' => $userType
            ];
        } else {
            $sql = "UPDATE ecouser SET username = :username, usertype = :usertype 
                    WHERE id = :id";
            $params = [
                ':id' => $id,
                ':username' => $username,
                ':usertype' => $userType
            ];
        }

        return $this->db->update($sql, $params);
    }

    public function deleteUser($id) {
        $sql = "DELETE FROM ecouser WHERE id = :id";
        return $this->db->delete($sql, [':id' => $id]);
    }
}