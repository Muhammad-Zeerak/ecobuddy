<?php
$pageTitle = 'Browse Eco Facilities';
include __DIR__ . '/../templates/header.php';
?>

    <h2 class="mb-4"><i class="fa-solid fa-list text-success me-2"></i>Browse Eco Facilities</h2>

    <div class="row mb-3">
        <div class="col-md-4">
            <form action="<?php echo SITE_URL; ?>/ecofacilities/search" method="get" class="d-flex">
                <input type="text" name="q" class="form-control me-2" placeholder="Search facilities...">
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>
        <div class="col-md-4">
            <form action="<?php echo SITE_URL; ?>/ecofacilities" method="get" class="d-flex">
                <select name="order" class="form-select me-2">
                    <option value="id" <?php echo (isset($_GET['order']) && $_GET['order'] == 'id') ? 'selected' : ''; ?>>ID</option>
                    <option value="title" <?php echo (isset($_GET['order']) && $_GET['order'] == 'title') ? 'selected' : ''; ?>>Title</option>
                    <option value="town" <?php echo (isset($_GET['order']) && $_GET['order'] == 'town') ? 'selected' : ''; ?>>Town</option>
                    <option value="postcode" <?php echo (isset($_GET['order']) && $_GET['order'] == 'postcode') ? 'selected' : ''; ?>>Postcode</option>
                </select>
                <select name="dir" class="form-select me-2">
                    <option value="ASC" <?php echo (!isset($_GET['dir']) || $_GET['dir'] == 'ASC') ? 'selected' : ''; ?>>Ascending</option>
                    <option value="DESC" <?php echo (isset($_GET['dir']) && $_GET['dir'] == 'DESC') ? 'selected' : ''; ?>>Descending</option>
                </select>
                <button type="submit" class="btn btn-outline-success"><i class="fa-solid fa-sort"></i></button>
            </form>
        </div>
    </div>

    <div class="row">
        <?php foreach ($facilities as $facility): ?>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fa-solid fa-map-marker-alt me-2"></i><?php echo htmlspecialchars($facility['title']); ?></h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><i class="fa-solid fa-tag me-2"></i><strong>Category:</strong> <?php echo htmlspecialchars($facility['category_name']); ?></p>
                        <p class="card-text"><i class="fa-solid fa-info-circle me-2"></i><?php echo htmlspecialchars($facility['description']); ?></p>
                        <p class="card-text">
                            <i class="fa-solid fa-location-dot me-2"></i>
                            <small class="text-muted">
                                <?php
                                $address = [];
                                if (!empty($facility['housenumber'])) $address[] = htmlspecialchars($facility['housenumber']);
                                if (!empty($facility['streetname'])) $address[] = htmlspecialchars($facility['streetname']);
                                if (!empty($facility['town'])) $address[] = htmlspecialchars($facility['town']);
                                if (!empty($facility['county'])) $address[] = htmlspecialchars($facility['county']);
                                if (!empty($facility['postcode'])) $address[] = htmlspecialchars($facility['postcode']);
                                echo implode(', ', $address);
                                ?>
                            </small>
                        </p>
                    </div>
                    <div class="card-footer">
                        <a href="<?php echo SITE_URL; ?>/ecofacilities/view/<?php echo $facility['id']; ?>" class="btn btn-primary"><i class="fa-solid fa-eye me-2"></i>View Details</a>
                        <?php if (isLoggedIn()): ?>
                            <a href="<?php echo SITE_URL; ?>/ecofacilities/add-status/<?php echo $facility['id']; ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-plus me-2"></i>Add Status Update</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                    <a class="page-link" href="<?php echo SITE_URL; ?>/ecofacilities?page=<?php echo $i; ?>&order=<?php echo isset($_GET['order']) ? $_GET['order'] : 'id'; ?>&dir=<?php echo isset($_GET['dir']) ? $_GET['dir'] : 'ASC'; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

<?php include __DIR__ . '/../templates/footer.php'; ?>