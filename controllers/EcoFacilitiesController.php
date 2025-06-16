<?php
require_once __DIR__ . '/../models/EcoFacility.php';
require_once __DIR__ . '/../models/EcoCategory.php';

class EcoFacilitiesController {
    private $facilityModel;
    private $categoryModel;

    public function __construct() {
        $this->facilityModel = new EcoFacility();
        $this->categoryModel = new EcoCategory();
    }

    // User methods
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $orderBy = isset($_GET['order']) ? sanitizeInput($_GET['order']) : 'id';
        $orderDir = isset($_GET['dir']) && strtoupper($_GET['dir']) === 'DESC' ? 'DESC' : 'ASC';

        // Get facilities with pagination
        $facilities = $this->facilityModel->getAllFacilities($page, $limit, $orderBy, $orderDir);
        $totalFacilities = $this->facilityModel->getTotalFacilities();
        $totalPages = ceil($totalFacilities / $limit);

        $categories = $this->categoryModel->getAllCategories();

        include __DIR__ . '/../views/ecofacilities/list.php';
    }

    public function view($id) {
        $id = (int)$id;
        $facility = $this->facilityModel->getFacilityById($id);

        if (!$facility) {
            redirect('/ecofacilities');
        }

        $statuses = $this->facilityModel->getFacilityStatuses($id);

        include __DIR__ . '/../views/ecofacilities/detail.php';
    }

    public function search() {
        $searchTerm = isset($_GET['q']) ? sanitizeInput($_GET['q']) : '';
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $orderBy = isset($_GET['order']) ? sanitizeInput($_GET['order']) : 'id';
        $orderDir = isset($_GET['dir']) && strtoupper($_GET['dir']) === 'DESC' ? 'DESC' : 'ASC';

        // Get all categories for the dropdown
        $categories = $this->categoryModel->getAllCategories();

        // Get selected category information if a category ID is provided
        $selectedCategory = null;
        if ($categoryId) {
            $selectedCategory = $this->categoryModel->getCategoryById($categoryId);
        }

        $facilities = $this->facilityModel->searchFacilities($searchTerm, $categoryId, $page, $limit, $orderBy, $orderDir);
        $totalFacilities = $this->facilityModel->getTotalSearchResults($searchTerm, $categoryId);
        $totalPages = ceil($totalFacilities / $limit);

        include __DIR__ . '/../views/ecofacilities/search.php';
    }

    public function addStatus($id) {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('/user/login');
        }

        $id = (int)$id;
        $facility = $this->facilityModel->getFacilityById($id);

        if (!$facility) {
            redirect('/ecofacilities');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $comment = sanitizeInput($_POST['comment']);

            if (empty($comment)) {
                $error = "Comment cannot be empty";
                include __DIR__ . '/../views/ecofacilities/add_status.php';
                return;
            }

            $result = $this->facilityModel->addStatus($id, $_SESSION['user_id'], $comment);

            if ($result) {
                redirect('/ecofacilities/view/' . $id);
            } else {
                $error = "Error adding status";
                include __DIR__ . '/../views/ecofacilities/add_status.php';
            }
        } else {
            include __DIR__ . '/../views/ecofacilities/add_status.php';
        }
    }

    // Manager methods
    public function manage() {
        // Check if user is a manager
        if (!isManager()) {
            redirect('/');
        }

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $orderBy = isset($_GET['order']) ? sanitizeInput($_GET['order']) : 'id';
        $orderDir = isset($_GET['dir']) && strtoupper($_GET['dir']) === 'DESC' ? 'DESC' : 'ASC';

        $facilities = $this->facilityModel->getAllFacilities($page, $limit, $orderBy, $orderDir);
        $totalFacilities = $this->facilityModel->getTotalFacilities();
        $totalPages = ceil($totalFacilities / $limit);

        include __DIR__ . '/../views/ecofacilities/manage.php';
    }

    public function create() {
        // Check if user is a manager
        if (!isManager()) {
            redirect('/');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate and sanitize inputs
            $title = sanitizeInput($_POST['title']);
            $category = (int)$_POST['category'];
            $description = sanitizeInput($_POST['description']);
            $houseNumber = sanitizeInput($_POST['housenumber']);
            $streetName = sanitizeInput($_POST['streetname']);
            $county = sanitizeInput($_POST['county']);
            $town = sanitizeInput($_POST['town']);
            $postcode = sanitizeInput($_POST['postcode']);
            $lng = (float)$_POST['lng'];
            $lat = (float)$_POST['lat'];

            // photo upload handling
            $photoName = null;
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../assets/images/facilities/';

                // Create directory if it doesn't exist
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $photoName = time() . '_' . sanitizeInput(basename($_FILES['photo']['name']));
                $uploadPath = $uploadDir . $photoName;

                // move the uploaded file into dir
                move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath);
            }

            // Basic validation
            if (empty($title) || empty($description) || empty($postcode) ||
                !is_numeric($lng) || !is_numeric($lat)) {
                $error = "Please fill in all required fields with valid data";
                $categories = $this->categoryModel->getAllCategories();
                include __DIR__ . '/../views/ecofacilities/create.php';
                return;
            }

            $data = [
                'title' => $title,
                'category' => $category,
                'description' => $description,
                'housenumber' => $houseNumber,
                'streetname' => $streetName,
                'county' => $county,
                'town' => $town,
                'postcode' => $postcode,
                'lng' => $lng,
                'lat' => $lat,
                'photo' => $photoName,
                'contributor' => $_SESSION['user_id']
            ];

            $facilityId = $this->facilityModel->createFacility($data);

            if ($facilityId) {
                redirect('/ecofacilities/manage');
            } else {
                $error = "Error creating facility";
                $categories = $this->categoryModel->getAllCategories();
                include __DIR__ . '/../views/ecofacilities/create.php';
            }
        } else {
            $categories = $this->categoryModel->getAllCategories();
            include __DIR__ . '/../views/ecofacilities/create.php';
        }
    }

    public function edit($id) {
        // Check if user is a manager
        if (!isManager()) {
            redirect('/');
        }

        $id = (int)$id;
        $facility = $this->facilityModel->getFacilityById($id);

        if (!$facility) {
            redirect('/ecofacilities/manage');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate and sanitize inputs
            $title = sanitizeInput($_POST['title']);
            $category = (int)$_POST['category'];
            $description = sanitizeInput($_POST['description']);
            $houseNumber = sanitizeInput($_POST['housenumber']);
            $streetName = sanitizeInput($_POST['streetname']);
            $county = sanitizeInput($_POST['county']);
            $town = sanitizeInput($_POST['town']);
            $postcode = sanitizeInput($_POST['postcode']);
            $lng = (float)$_POST['lng'];
            $lat = (float)$_POST['lat'];

            // Handle photo upload
            $photoName = $facility['photo']; // Keep existing photo by default
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../assets/images/facilities/';

                // Create directory if it doesn't exist
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $photoName = time() . '_' . sanitizeInput(basename($_FILES['photo']['name']));
                $uploadPath = $uploadDir . $photoName;

                move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath);

                // Delete old photo if it exists and is different
                if ($facility['photo'] && $facility['photo'] != $photoName) {
                    $oldPhotoPath = $uploadDir . $facility['photo'];
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }
            }

            // Basic validation
            if (empty($title) || empty($description) || empty($postcode) ||
                !is_numeric($lng) || !is_numeric($lat)) {
                $error = "Please fill in all required fields with valid data";
                $categories = $this->categoryModel->getAllCategories();
                include __DIR__ . '/../views/ecofacilities/edit.php';
                return;
            }

            $data = [
                'title' => $title,
                'category' => $category,
                'description' => $description,
                'housenumber' => $houseNumber,
                'streetname' => $streetName,
                'county' => $county,
                'town' => $town,
                'postcode' => $postcode,
                'lng' => $lng,
                'lat' => $lat,
                'photo' => $photoName
            ];

            $result = $this->facilityModel->updateFacility($id, $data);

            if ($result) {
                redirect('/ecofacilities/manage');
            } else {
                $error = "Error updating facility";
                $categories = $this->categoryModel->getAllCategories();
                include __DIR__ . '/../views/ecofacilities/edit.php';
            }
        } else {
            $categories = $this->categoryModel->getAllCategories();
            include __DIR__ . '/../views/ecofacilities/edit.php';
        }
    }

    public function delete($id) {
        // Check if user is a manager
        if (!isManager()) {
            redirect('/');
        }

        $id = (int)$id;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->facilityModel->deleteFacility($id);

            if ($result) {
                redirect('/ecofacilities/manage');
            } else {
                $error = "Error deleting facility";
                $facilities = $this->facilityModel->getAllFacilities();
                include __DIR__ . '/../views/ecofacilities/manage.php';
            }
        } else {
            $facility = $this->facilityModel->getFacilityById($id);

            if (!$facility) {
                redirect('/ecofacilities/manage');
            }

            include __DIR__ . '/../views/ecofacilities/delete.php';
        }
    }

    public function deleteStatus($id) {
        // Check if user is a manager
        if (!isManager()) {
            redirect('/');
        }

        $id = (int)$id;
        $status = $this->facilityModel->getStatusById($id);

        if (!$status) {
            redirect('/ecofacilities/manage');
        }

        $facilityId = $status['facilityid'];
        $result = $this->facilityModel->deleteStatus($id);

        if ($result) {
            redirect('/ecofacilities/view/' . $facilityId);
        } else {
            $error = "Error deleting status";
            $facility = $this->facilityModel->getFacilityById($facilityId);
            $statuses = $this->facilityModel->getFacilityStatuses($facilityId);
            include __DIR__ . '/../views/ecofacilities/detail.php';
        }
    }

    // Category management methods (manager only)
    public function manageCategories() {
        // Check if user is a manager
        if (!isManager()) {
            redirect('/');
        }

        $categories = $this->categoryModel->getAllCategories();
        include __DIR__ . '/../views/ecofacilities/categories.php';
    }

    public function createCategory() {
        // Check if user is a manager
        if (!isManager()) {
            redirect('/');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = sanitizeInput($_POST['name']);

            if (empty($name)) {
                $error = "Category name cannot be empty";
                include __DIR__ . '/../views/ecofacilities/create_category.php';
                return;
            }

            $categoryId = $this->categoryModel->createCategory($name);

            if ($categoryId) {
                redirect('/ecofacilities/categories');
            } else {
                $error = "Error creating category. Name may already exist.";
                include __DIR__ . '/../views/ecofacilities/create_category.php';
            }
        } else {
            include __DIR__ . '/../views/ecofacilities/create_category.php';
        }
    }

    public function editCategory($id) {
        // Check if user is a manager
        if (!isManager()) {
            redirect('/');
        }

        $id = (int)$id;
        $category = $this->categoryModel->getCategoryById($id);

        if (!$category) {
            redirect('/ecofacilities/categories');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = sanitizeInput($_POST['name']);

            if (empty($name)) {
                $error = "Category name cannot be empty";
                include __DIR__ . '/../views/ecofacilities/edit_category.php';
                return;
            }

            $result = $this->categoryModel->updateCategory($id, $name);

            if ($result) {
                redirect('/ecofacilities/categories');
            } else {
                $error = "Error updating category. Name may already exist.";
                include __DIR__ . '/../views/ecofacilities/edit_category.php';
            }
        } else {
            include __DIR__ . '/../views/ecofacilities/edit_category.php';
        }
    }

    public function deleteCategory($id) {
        // Check if user is a manager
        if (!isManager()) {
            redirect('/');
        }

        $id = (int)$id;
        $category = $this->categoryModel->getCategoryById($id);

        if (!$category) {
            redirect('/ecofacilities/categories');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->categoryModel->deleteCategory($id);

            if ($result) {
                redirect('/ecofacilities/categories');
            } else {
                $error = "Error deleting category. It may be in use by facilities.";
                $categories = $this->categoryModel->getAllCategories();
                include __DIR__ . '/../views/ecofacilities/categories.php';
            }
        } else {
            include __DIR__ . '/../views/ecofacilities/delete_category.php';
        }
    }

    public function showMap() {
        // Get categories for filter dropdown
        $categories = $this->categoryModel->getAllCategories();

        include __DIR__ . '/../views/ecofacilities/map.php';
    }
}