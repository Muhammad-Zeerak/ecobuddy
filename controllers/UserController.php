<?php
require_once __DIR__ . '/../models/EcoUser.php';
require_once __DIR__ . '/../models/EcoUserType.php';

class UserController {
    private $userModel;
    private $userTypeModel;

    public function __construct() {
        $this->userModel = new EcoUser();
        $this->userTypeModel = new EcoUserType();
    }

    public function login() {
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = sanitizeInput($_POST['username']);
            $password = $_POST['password'];

            $user = $this->userModel->authenticate($username, $password);

            if ($user) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_type'] = $user['usertype'];

                // Redirect based on user type
                if ($user['usertype'] == 1) { // Manager
                    redirect('/ecofacilities/manage');
                } else { // Regular user
                    redirect('/ecofacilities');
                }
            } else {
                $error = "Invalid username or password";
                include __DIR__ . '/../views/user/login.php';
            }
        } else {
            // Display login form
            include __DIR__ . '/../views/user/login.php';
        }
    }

    public function logout() {
        // Unset all session variables
        $_SESSION = [];

        // Destroy the session
        session_destroy();

        // Display logout confirmation
        include __DIR__ . '/../views/user/logout.php';
    }

    public function profile() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('/user/login');
        }

        $user = $this->userModel->getUserById($_SESSION['user_id']);
        include __DIR__ . '/../views/user/profile.php';
    }

    // Manager-only methods for user management
    public function manageUsers() {
        // Check if user is a manager
        if (!isManager()) {
            redirect('/');
        }

        $users = $this->userModel->getAllUsers();
        include __DIR__ . '/../views/user/manage.php';
    }

    public function createUser() {
        // Check if user is a manager
        if (!isManager()) {
            redirect('/');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = sanitizeInput($_POST['username']);
            $password = $_POST['password'];
            $userType = (int)$_POST['user_type'];

            // Validate password strength
            if (strlen($password) < 12 || !preg_match('/[A-Z]/', $password) ||
                !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) ||
                !preg_match('/[^A-Za-z0-9]/', $password)) {

                $error = "Password must be at least 12 characters long and include uppercase letters, 
                         lowercase letters, numbers, and special characters.";
                $userTypes = $this->userTypeModel->getAllUserTypes();
                include __DIR__ . '/../views/user/create.php';
                return;
            }

            $userId = $this->userModel->createUser($username, $password, $userType);

            if ($userId) {
                redirect('/user/manage');
            } else {
                $error = "Error creating user. Username may already exist.";
                $userTypes = $this->userTypeModel->getAllUserTypes();
                include __DIR__ . '/../views/user/create.php';
            }
        } else {
            $userTypes = $this->userTypeModel->getAllUserTypes();
            include __DIR__ . '/../views/user/create.php';
        }
    }

    public function editUser($id) {
        // Check if user is a manager
        if (!isManager()) {
            redirect('/');
        }

        $id = (int)$id;
        $user = $this->userModel->getUserById($id);

        if (!$user) {
            redirect('/user/manage');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = sanitizeInput($_POST['username']);
            $userType = (int)$_POST['user_type'];
            $password = !empty($_POST['password']) ? $_POST['password'] : null;

            // Validate password if provided
            if ($password && (strlen($password) < 12 || !preg_match('/[A-Z]/', $password) ||
                    !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) ||
                    !preg_match('/[^A-Za-z0-9]/', $password))) {

                $error = "Password must be at least 12 characters long and include uppercase letters, 
                         lowercase letters, numbers, and special characters.";
                $userTypes = $this->userTypeModel->getAllUserTypes();
                include __DIR__ . '/../views/user/edit.php';
                return;
            }

            $result = $this->userModel->updateUser($id, $username, $userType, $password);

            if ($result) {
                redirect('/user/manage');
            } else {
                $error = "Error updating user. Username may already exist.";
                $userTypes = $this->userTypeModel->getAllUserTypes();
                include __DIR__ . '/../views/user/edit.php';
            }
        } else {
            $userTypes = $this->userTypeModel->getAllUserTypes();
            include __DIR__ . '/../views/user/edit.php';
        }
    }

    public function deleteUser($id) {
        // Check if user is a manager
        if (!isManager()) {
            redirect('/');
        }

        $id = (int)$id;

        // Don't allow deleting self
        if ($id === $_SESSION['user_id']) {
            $error = "You cannot delete your own account.";
            $users = $this->userModel->getAllUsers();
            include __DIR__ . '/../views/user/manage.php';
            return;
        }

        $result = $this->userModel->deleteUser($id);

        if ($result) {
            redirect('/user/manage');
        } else {
            $error = "Error deleting user.";
            $users = $this->userModel->getAllUsers();
            include __DIR__ . '/../views/user/manage.php';
        }
    }
}