<?php
$pageTitle = 'Eco Facilities Map';
include __DIR__ . '/../templates/header.php';
?>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fa-solid fa-map-location-dot me-2"></i>Eco Facilities Map</h4>
                </div>
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Facility list sidebar with infinite scrolling -->
                        <div class="col-md-3 border-end">
                            <div class="p-3">
                                <!-- Search and filter controls -->
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-search"></i></span>
                                        <input type="search" id="search-input" class="form-control" placeholder="Search facilities...">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="category-filter" class="form-label">Filter by Category</label>
                                    <select id="category-filter" class="form-select">
                                        <option value="">All Categories</option>
                                        <!-- Categories will be populated via JavaScript -->
                                    </select>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="sort-by" class="form-label">Sort By</label>
                                        <select id="sort-by" class="form-select">
                                            <option value="id">ID</option>
                                            <option value="title">Name</option>
                                            <option value="town">Town</option>
                                            <option value="postcode">Postcode</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="sort-dir" class="form-label">Order</label>
                                        <select id="sort-dir" class="form-select">
                                            <option value="ASC">Ascending</option>
                                            <option value="DESC">Descending</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Status info -->
                                <div id="status-info" class="small text-muted mb-2"></div>
                                <!-- Error display -->
                                <div id="facilities-error" class="alert alert-danger p-2 small" style="display: none;"></div>
                            </div>

                            <!-- Loading indicator -->
                            <div id="loading-indicator" class="text-center p-2" style="display: none;">
                                <div class="spinner-border spinner-border-sm text-success me-2" role="status"></div>
                                <span>Loading...</span>
                            </div>

                            <!-- Facility list with infinite scrolling -->
                            <div id="facility-list" class="facility-list-container" style="max-height: 500px; overflow-y: auto;">
                                <!-- Facilities will be populated via JavaScript -->
                                <div class="text-center p-3">
                                    <div class="spinner-border text-success" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2">Loading facilities...</p>
                                </div>
                                <div class="sentinel"></div>
                            </div>

                            <!-- Load more button-->
                            <div class="text-center py-3">
                                <button id="load-more-btn" class="btn btn-outline-success btn-sm">
                                    Load More
                                </button>
                            </div>
                        </div>

                        <!-- Map container -->
                        <div class="col-md-9">
                            <div id="map-container" style="height: 600px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="statusModalLabel">Status Updates</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="status-modal-body">
                    <!-- Content will be dynamically added by JS -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
          integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
          crossorigin=""/>

    <!-- Map custom styles -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/mapStyles.css">

    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
            integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
            crossorigin=""></script>

    <!-- Pass user login status -->
    <script>
        window.SITE_URL = '<?php echo SITE_URL; ?>';
        window.isUserLoggedIn = <?php echo isLoggedIn() ? 'true' : 'false'; ?>;
        window.currentUserId = <?php echo isLoggedIn() ? $_SESSION['user_id'] : 'null'; ?>;
    </script>

    <!-- Our custom JavaScript files -->
    <script src="<?php echo SITE_URL; ?>/assets/js/GeolocationService.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/InfiniteScroller.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/FacilityMarker.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/MapController.js"></script>

    <!-- Initialize map -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mapController = new MapController('map-container', 'facility-list');
        });
    </script>

<?php include __DIR__ . '/../templates/footer.php'; ?>