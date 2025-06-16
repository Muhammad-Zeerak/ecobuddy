// Class for handling infinite scrolling functionality
class InfiniteScroller {
    constructor(mapController, containerId) {
        // Store reference to map controller
        this.mapController = mapController;

        // Container element for facility list
        this.container = document.getElementById(containerId);

        // Pagination parameters
        this.page = 1;
        this.limit = 5; // Items per page/request
        this.totalPages = null;
        this.totalItems = 0;
        this.loadedItems = 0;

        // Loading state
        this.isLoading = false;
        this.hasMoreItems = true;

        // Maximum items to keep in DOM to prevent memory issues
        this.maxItemsInDom = 100;

        // Search parameters
        this.searchParams = {
            q: '',
            category: '',
            sort: 'id',
            dir: 'ASC'
        };

        // Initialize observer for infinite scrolling
        this.initIntersectionObserver();

        // Initialize event listeners
        this.initEventListeners();
    }

    // Initialize Intersection Observer for detecting when user scrolls to bottom
    initIntersectionObserver() {
        // Create a sentinel element that will trigger loading more content
        this.sentinel = document.createElement('div');
        this.sentinel.className = 'sentinel';
        this.sentinel.style.height = '1px';

        // Add sentinel to container if it exists
        if (this.container) {
            this.container.appendChild(this.sentinel);
        }

        // Create intersection observer
        this.observer = new IntersectionObserver(entries => {
            // If sentinel is visible and we're not already loading
            if (entries[0].isIntersecting && !this.isLoading && this.hasMoreItems) {
                this.loadMoreItems();
            }
        }, {
            root: null,
            rootMargin: '0px',
            threshold: 0.1 // Trigger when 10% of sentinel is visible
        });

        // Start observing sentinel
        if (this.sentinel) {
            this.observer.observe(this.sentinel);
        }
    }

    // Initialize event listeners
    initEventListeners() {
        // Load more button (as fallback) in case browser does not support loading by default
        const loadMoreBtn = document.getElementById('load-more-btn');
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', () => {
                if (!this.isLoading && this.hasMoreItems) {
                    this.loadMoreItems();
                }
            });
        }

        // Search input
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            let debounceTimer;

            searchInput.addEventListener('input', (e) => {
                // Clear previous timer
                if (debounceTimer) {
                    clearTimeout(debounceTimer);
                }

                // Set new timer for debounce
                debounceTimer = setTimeout(() => {
                    this.searchParams.q = e.target.value;
                    this.resetScroller();
                    this.loadItems();
                }, 300); // 300ms debounce delay
            });
        }

        // Category filter
        const categoryFilter = document.getElementById('category-filter');
        if (categoryFilter) {
            categoryFilter.addEventListener('change', (e) => {
                this.searchParams.category = e.target.value;
                this.resetScroller();
                this.loadItems();
            });
        }

        // Sort by
        const sortBy = document.getElementById('sort-by');
        if (sortBy) {
            sortBy.addEventListener('change', (e) => {
                this.searchParams.sort = e.target.value;
                this.resetScroller();
                this.loadItems();
            });
        }

        // Sort direction
        const sortDir = document.getElementById('sort-dir');
        if (sortDir) {
            sortDir.addEventListener('change', (e) => {
                this.searchParams.dir = e.target.value;
                this.resetScroller();
                this.loadItems();
            });
        }
    }

    // Load initial items
    loadItems() {
        this.isLoading = true;
        this.page = 1;
        this.showLoadingIndicator();

        // Clear container
        if (this.container) {
            // Keep the sentinel element
            const sentinel = this.sentinel;
            this.container.innerHTML = '';
            this.container.appendChild(sentinel);
        }

        // Build query parameters
        const params = new URLSearchParams();
        params.append('page', this.page);
        params.append('limit', this.limit);

        if (this.searchParams.q) {
            params.append('q', this.searchParams.q);
        }

        if (this.searchParams.category) {
            params.append('category', this.searchParams.category);
        }

        params.append('order', this.searchParams.sort);
        params.append('dir', this.searchParams.dir);

        // Fetch data
        fetch(`${window.SITE_URL}/api/facilities/paginate?${params.toString()}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                this.handleFacilitiesResponse(data);
            })
            .catch(error => {
                console.error('Error loading facilities:', error);
                this.showError('Could not load facilities. Please try again later.');
            })
            .finally(() => {
                this.isLoading = false;
                this.hideLoadingIndicator();
                this.updateLoadMoreButton();
            });
    }

    // Load more items (next page)
    loadMoreItems() {
        if (this.isLoading || !this.hasMoreItems) return;

        this.isLoading = true;
        this.page++;
        this.showLoadingIndicator();

        // Build query parameters
        const params = new URLSearchParams();
        params.append('page', this.page);
        params.append('limit', this.limit);

        if (this.searchParams.q) {
            params.append('q', this.searchParams.q);
        }

        if (this.searchParams.category) {
            params.append('category', this.searchParams.category);
        }

        params.append('order', this.searchParams.sort);
        params.append('dir', this.searchParams.dir);

        // Fetch data
        fetch(`${window.SITE_URL}/api/facilities/paginate?${params.toString()}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                this.handleMoreFacilitiesResponse(data);
            })
            .catch(error => {
                console.error('Error loading more facilities:', error);
                this.showError('Could not load more facilities. Please try again later.');
                // Reset page number on error
                this.page--;
            })
            .finally(() => {
                this.isLoading = false;
                this.hideLoadingIndicator();
                this.updateLoadMoreButton();
            });
    }

    // Handle initial facilities response
    handleFacilitiesResponse(response) {
        if (!response || !response.facilities) {
            this.showError('Invalid response from server');
            return;
        }

        // Store total info
        this.totalItems = response.total;
        this.totalPages = response.pages;
        this.loadedItems = response.facilities.length;

        // Update facilities in map controller
        this.mapController.updateFacilities(response.facilities);

        // Update has more items flag
        this.hasMoreItems = this.page < this.totalPages;

        // Update status info
        this.updateStatusInfo();
    }

    // Handle more facilities response (for pagination)
    handleMoreFacilitiesResponse(response) {
        if (!response || !response.facilities) {
            this.showError('Invalid response from server');
            return;
        }

        // Update total info
        this.totalItems = response.total;
        this.totalPages = response.pages;
        this.loadedItems += response.facilities.length;

        // Add new facilities to map controller
        this.mapController.addMoreFacilities(response.facilities);

        // Update has more items flag
        this.hasMoreItems = this.page < this.totalPages;

        // Update status info
        this.updateStatusInfo();

        // Prune DOM if necessary to prevent memory issues
        this.pruneItemsIfNeeded();
    }

    // Prune the oldest items from DOM if we're over the limit
    pruneItemsIfNeeded() {
        if (!this.container) return;

        const facilityItems = this.container.querySelectorAll('.facility-item');

        if (facilityItems.length > this.maxItemsInDom) {
            const itemsToPrune = facilityItems.length - this.maxItemsInDom;

            // Remove the oldest items (top of the list)
            for (let i = 0; i < itemsToPrune; i++) {
                if (facilityItems[i] && facilityItems[i].parentNode) {
                    facilityItems[i].parentNode.removeChild(facilityItems[i]);
                }
            }

            console.log(`Pruned ${itemsToPrune} items from DOM to prevent memory issues.`);
        }
    }

    // Reset scroller to initial state
    resetScroller() {
        this.page = 1;
        this.totalPages = null;
        this.totalItems = 0;
        this.loadedItems = 0;
        this.hasMoreItems = true;
    }

    // Show loading indicator
    showLoadingIndicator() {
        const loadingIndicator = document.getElementById('loading-indicator');
        if (loadingIndicator) {
            loadingIndicator.style.display = 'block';
        }

        // Update load more button if it exists
        const loadMoreBtn = document.getElementById('load-more-btn');
        if (loadMoreBtn) {
            loadMoreBtn.disabled = true;
            loadMoreBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
        }
    }

    // Hide loading indicator
    hideLoadingIndicator() {
        const loadingIndicator = document.getElementById('loading-indicator');
        if (loadingIndicator) {
            loadingIndicator.style.display = 'none';
        }

        // Update load more button if it exists
        const loadMoreBtn = document.getElementById('load-more-btn');
        if (loadMoreBtn) {
            loadMoreBtn.disabled = false;
            loadMoreBtn.innerHTML = 'Load More';
        }
    }

    // Update load more button state
    updateLoadMoreButton() {
        const loadMoreBtn = document.getElementById('load-more-btn');
        if (loadMoreBtn) {
            if (!this.hasMoreItems) {
                loadMoreBtn.disabled = true;
                loadMoreBtn.innerHTML = 'No More Items';
            } else {
                loadMoreBtn.disabled = false;
                loadMoreBtn.innerHTML = 'Load More';
            }
        }
    }

    // Update status information
    updateStatusInfo() {
        const statusInfo = document.getElementById('status-info');
        if (statusInfo) {
            statusInfo.textContent = `Showing ${this.loadedItems} of ${this.totalItems} facilities`;
        }
    }

    // Show error message
    showError(message) {
        const errorElement = document.getElementById('facilities-error');
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.style.display = 'block';

            // Hide after 5 seconds
            setTimeout(() => {
                errorElement.style.display = 'none';
            }, 5000);
        }
    }
}