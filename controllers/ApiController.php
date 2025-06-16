<?php
require_once __DIR__ . '/../models/EcoFacility.php';
require_once __DIR__ . '/../models/EcoCategory.php';

class ApiController {
    private $facilityModel;
    private $categoryModel;

    public function __construct() {
        $this->facilityModel = new EcoFacility();
        $this->categoryModel = new EcoCategory();
    }

    // Get all facilities as JSON
    public function getFacilities() {
        // Set content type to JSON
        header('Content-Type: application/json');

        // Get all facilities
        $facilities = $this->facilityModel->getAllFacilities(1, 1000); // Get up to 1000 facilities

        // Return as JSON
        echo json_encode($facilities);
    }

    // Paginated facilities endpoint for infinite scrolling
    public function paginateFacilities() {
        // Set content type to JSON
        header('Content-Type: application/json');

        // Get pagination parameters
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;

        // Safety check for limit - don't allow more than 100 items per page
        if ($limit > 100) {
            $limit = 100;
        }

        // Get search parameters
        $searchTerm = isset($_GET['q']) ? sanitizeInput($_GET['q']) : '';
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
        $orderBy = isset($_GET['order']) ? sanitizeInput($_GET['order']) : 'id';
        $orderDir = isset($_GET['dir']) && strtoupper($_GET['dir']) === 'DESC' ? 'DESC' : 'ASC';

        // Get paginated facilities
        if (!empty($searchTerm) || !empty($categoryId)) {
            // Perform search with pagination
            $facilities = $this->facilityModel->searchFacilities($searchTerm, $categoryId, $page, $limit, $orderBy, $orderDir);
            $totalCount = $this->facilityModel->getTotalSearchResults($searchTerm, $categoryId);
        } else {
            // Get all facilities with pagination
            $facilities = $this->facilityModel->getAllFacilities($page, $limit, $orderBy, $orderDir);
            $totalCount = $this->facilityModel->getTotalFacilities();
        }

        // Calculate total pages
        $totalPages = ceil($totalCount / $limit);

        // Return paginated response
        $response = [
            'facilities' => $facilities,
            'total' => $totalCount,
            'pages' => $totalPages,
            'page' => $page,
            'limit' => $limit
        ];

        echo json_encode($response);
    }

    // Get facility by ID
    public function getFacility($id) {
        // Set content type to application/json
        header('Content-Type: application/json');

        // Get facility
        $facility = $this->facilityModel->getFacilityById($id);

        if (!$facility) {
            http_response_code(404);
            echo json_encode(['error' => 'Facility not found']);
            return;
        }

        // Return as JSON
        echo json_encode($facility);
    }

    // Get facility statuses
    public function getStatuses($facilityId) {
        // Set content type to application/json
        header('Content-Type: application/json');

        // Check if facility exists
        $facility = $this->facilityModel->getFacilityById($facilityId);

        if (!$facility) {
            http_response_code(404);
            echo json_encode(['error' => 'Facility not found']);
            return;
        }

        // Get statuses
        $statuses = $this->facilityModel->getFacilityStatuses($facilityId);

        // Return as JSON
        echo json_encode($statuses);
    }

    // Add status via AJAX
    public function addStatus() {
        // Set content type to application/json
        header('Content-Type: application/json');

        // Check if user is logged in
        if (!isLoggedIn()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'You must be logged in to add a status']);
            return;
        }

        // Check if it's a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        // Validate inputs
        $facilityId = isset($_POST['facilityId']) ? (int)$_POST['facilityId'] : 0;
        $comment = isset($_POST['comment']) ? sanitizeInput($_POST['comment']) : '';

        if (empty($facilityId) || empty($comment)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Facility ID and comment are required']);
            return;
        }

        // Check if facility exists
        $facility = $this->facilityModel->getFacilityById($facilityId);

        if (!$facility) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Facility not found']);
            return;
        }

        // Add status
        $result = $this->facilityModel->addStatus($facilityId, $_SESSION['user_id'], $comment);

        if ($result) {
            // Get the newly added status
            $statuses = $this->facilityModel->getFacilityStatuses($facilityId);
            $newStatus = count($statuses) > 0 ? $statuses[0] : null;

            echo json_encode([
                'success' => true,
                'message' => 'Status added successfully',
                'status' => $newStatus
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error adding status']);
        }
    }

    // Search facilities via AJAX
    public function searchFacilities() {
        // Set content type to application/json
        header('Content-Type: application/json');

        // Get search parameters
        $searchTerm = isset($_GET['q']) ? sanitizeInput($_GET['q']) : '';
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;

        // Search facilities
        $facilities = $this->facilityModel->searchFacilities($searchTerm, $categoryId, 1, 1000);

        // Return as JSON
        echo json_encode($facilities);
    }

    // Get all categories
    public function getCategories() {
        // Set content type to application/json
        header('Content-Type: application/json');

        // Get categories
        $categories = $this->categoryModel->getAllCategories();

        // Return as JSON
        echo json_encode($categories);
    }

    public function updateStatus() {
        // Set content type to application/json
        header('Content-Type: application/json');

        // Check if user is logged in
        if (!isLoggedIn()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'You must be logged in to update a status']);
            return;
        }

        // Check if it's a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        // Validate inputs
        $statusId = isset($_POST['statusId']) ? (int)$_POST['statusId'] : 0;
        $comment = isset($_POST['comment']) ? sanitizeInput($_POST['comment']) : '';

        if (empty($statusId) || empty($comment)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Status ID and comment are required']);
            return;
        }

        // Get the status to verify ownership
        $status = $this->facilityModel->getStatusById($statusId);

        if (!$status) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Status not found']);
            return;
        }

        // Check if the current user owns this status or is a manager
        if ($status['userId'] != $_SESSION['user_id'] && !isManager()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'You are not authorized to edit this status']);
            return;
        }

        // Update the status
        $result = $this->facilityModel->updateStatus($statusId, $comment);

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error updating status']);
        }
    }
}