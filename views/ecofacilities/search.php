<?php
$pageTitle = 'Search Eco Facilities';
include __DIR__ . '/../templates/header.php';
?>

    <h2 class="mb-4">Search Eco Facilities</h2>

    <div class="card mb-4">
        <div class="card-body">
            <form action="<?php echo SITE_URL; ?>/ecofacilities/search" method="get">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="q" class="form-label">Search Term</label>
                        <input type="text" class="form-control" id="q" name="q" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>" placeholder="Title, description, address...">
                    </div>
                    <div class="col-md-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $category['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="order" class="form-label">Order By</label>
                        <select class="form-select" id="order" name="order">
                            <option value="id" <?php echo (isset($_GET['order']) && $_GET['order'] == 'id') ? 'selected' : ''; ?>>ID</option>
                            <option value="title" <?php echo (isset($_GET['order']) && $_GET['order'] == 'title') ? 'selected' : ''; ?>>Title</option>
                            <option value="town" <?php echo (isset($_GET['order']) && $_GET['order'] == 'town') ? 'selected' : ''; ?>>Town</option>
                            <option value="postcode" <?php echo (isset($_GET['order']) && $_GET['order'] == 'postcode') ? 'selected' : ''; ?>>Postcode</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="dir" class="form-label">Direction</label>
                        <select class="form-select" id="dir" name="dir">
                            <option value="ASC" <?php echo (!isset($_GET['dir']) || $_GET['dir'] == 'ASC') ? 'selected' : ''; ?>>Ascending</option>
                            <option value="DESC" <?php echo (isset($_GET['dir']) && $_GET['dir'] == 'DESC') ? 'selected' : ''; ?>>Descending</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php if (isset($_GET['q']) || isset($_GET['category'])): ?>
    <h4>Search Results <?php echo isset($_GET['q']) ? 'for "' . htmlspecialchars($_GET['q']) . '"' : ''; ?></h4>

    <?php if (count($facilities) > 0): ?>
        <div class="row">
            <?php foreach ($facilities as $facility): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><?php echo htmlspecialchars($facility['title']); ?></h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><strong>Category:</strong> <?php echo htmlspecialchars($facility['category_name']); ?></p>
                            <p class="card-text"><?php echo htmlspecialchars($facility['description']); ?></p>
                            <p class="card-text">
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
                            <a href="<?php echo SITE_URL; ?>/ecofacilities/view/<?php echo $facility['id']; ?>" class="btn btn-primary">View Details</a>
                            <?php if (isLoggedIn()): ?>
                                <a href="<?php echo SITE_URL; ?>/ecofacilities/add-status/<?php echo $facility['id']; ?>" class="btn btn-outline-secondary">Add Status Update</a>
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
                        <a class="page-link" href="<?php echo SITE_URL; ?>/ecofacilities/search?q=<?php echo isset($_GET['q']) ? urlencode($_GET['q']) : ''; ?>&category=<?php echo isset($_GET['category']) ? $_GET['category'] : ''; ?>&page=<?php echo $i; ?>&order=<?php echo isset($_GET['order']) ? $_GET['order'] : 'id'; ?>&dir=<?php echo isset($_GET['dir']) ? $_GET['dir'] : 'ASC'; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php else: ?>
        <div class="alert alert-info">No facilities found matching your search criteria.</div>
    <?php endif; ?>
<?php endif; ?>

<?php include __DIR__ . '/../templates/footer.php'; ?>