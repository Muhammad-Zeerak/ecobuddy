// Main controller for the map functionality using Leaflet
class MapController {
    constructor(mapContainerId, facilityListContainerId) {
        // Store DOM element IDs
        this.mapContainerId = mapContainerId;
        this.facilityListContainerId = facilityListContainerId;

        // Initialize services
        this.geolocationService = GeolocationService.getInstance();

        // Initialize properties
        this.map = null;
        this.markers = [];
        this.facilities = [];
        this.categories = [];
        this.isUserLoggedIn = window.isUserLoggedIn || false;
        this.currentUserId = window.currentUserId || null;
        this.userMarker = null;

        // Initialize map
        this.initMap();

        // Load categories
        this.loadCategories();

        // Set up event handlers
        this.setupEventHandlers();

        // Initialize infinite scroller after map initialization
        this.infiniteScroller = new InfiniteScroller(this, facilityListContainerId);
    }

    // Initialize the Leaflet Map
    async initMap() {
        try {
            // Create the map with a default center
            this.map = L.map(this.mapContainerId, {
                zoomControl: true,
                attributionControl: true
            });

            this.map.mapController = this;

            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(this.map);

            // Get user's location and center map
            this.geolocationService.getUserLocation().then(position => {
                // Set view to user location with a closer zoom level
                this.map.setView([position.lat, position.lng], 15);

                // Add a marker for user's location
                this.userMarker = L.circleMarker([position.lat, position.lng], {
                    color: '#4a89dc',
                    fillColor: '#4a89dc',
                    fillOpacity: 0.5,
                    radius: 8,
                    weight: 2
                }).addTo(this.map);

                // Add a pulsing effect to make it more visible
                this.userMarker._path.classList.add('user-location-pulse');

                this.userMarker.bindPopup("Your Current Location").openPopup();

                // Initialize the infinite scroller once we have user location
                this.infiniteScroller.loadItems();
            }).catch(error => {
                // If geolocation fails, use default view (Manchester city center)
                console.warn('Geolocation failed:', error);
                this.map.setView([53.480759, -2.242631], 13);

                // Initialize the infinite scroller anyway
                this.infiniteScroller.loadItems();
            });
        } catch (error) {
            console.error('Error initializing map:', error);
            this.showError('Could not initialize map. Please refresh the page.');
        }
    }

    // Update facilities and markers with new data
    updateFacilities(facilities) {
        // Clear existing markers
        this.clearMarkers();

        // Update facilities array
        this.facilities = facilities;

        // Create markers for each facility
        this.createMarkers();

        // Render facility list
        this.renderFacilityList(true); // true = replace existing list
    }

    // Add more facilities (for infinite scrolling)
    addMoreFacilities(facilities) {
        // Add to existing facilities array
        this.facilities = this.facilities.concat(facilities);

        // Create markers for new facilities
        this.createMarkersForFacilities(facilities);

        // Append to facility list
        this.renderMoreFacilities(facilities);
    }

    // Clear all markers from map
    clearMarkers() {
        this.markers.forEach(marker => {
            if (this.map.hasLayer(marker.marker)) {
                this.map.removeLayer(marker.marker);
            }
        });
        this.markers = [];
    }

    // Load categories via AJAX
    loadCategories() {
        fetch(`${SITE_URL}/api/categories`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                this.categories = data;
                this.populateCategoryFilter();
            })
            .catch(error => {
                console.error('Error loading categories:', error);
            });
    }

    // Populate category filter dropdown
    populateCategoryFilter() {
        const categoryFilter = document.getElementById('category-filter');
        if (!categoryFilter) return;

        categoryFilter.innerHTML = '<option value="">All Categories</option>';

        this.categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            categoryFilter.appendChild(option);
        });
    }

    // Create markers for all facilities
    createMarkers() {
        this.markers = [];
        this.createMarkersForFacilities(this.facilities);
    }

    // Create markers for a subset of facilities
    createMarkersForFacilities(facilities) {
        facilities.forEach(facility => {
            // Check if a marker already exists for this facility
            const existingMarker = this.markers.find(m => m.facility.id === facility.id);

            if (!existingMarker) {
                const marker = new FacilityMarker(facility, this.map);
                this.markers.push(marker);
            }
        });
    }

    // Render the facility list
    renderFacilityList(replace = true) {
        const container = document.getElementById(this.facilityListContainerId);
        if (!container) return;

        if (replace) {
            // Save the sentinel div if it exists (for infinite scrolling)
            const sentinel = container.querySelector('.sentinel');
            container.innerHTML = '';

            // Add back the sentinel if it existed
            if (sentinel) {
                container.appendChild(sentinel);
            }
        }

        if (this.facilities.length === 0) {
            if (replace) {
                container.innerHTML = '<div class="p-3 text-center text-muted">No facilities found</div>';

                // Add back the sentinel if necessary
                const sentinel = document.createElement('div');
                sentinel.className = 'sentinel';
                container.appendChild(sentinel);
            }
            return;
        }

        // Render or append facilities
        if (replace) {
            this.renderMoreFacilities(this.facilities);
        }
    }

    // Render more facilities (append to existing list)
    renderMoreFacilities(facilities) {
        const container = document.getElementById(this.facilityListContainerId);
        if (!container) return;

        // Get sentinel element if it exists
        const sentinel = container.querySelector('.sentinel');

        // Create a document fragment for better performance
        const fragment = document.createDocumentFragment();

        facilities.forEach(facility => {
            const facilityElement = document.createElement('div');
            facilityElement.className = 'facility-item card mb-2';
            facilityElement.setAttribute('data-facility-id', facility.id);

            facilityElement.innerHTML = `
                <div class="card-body py-2">
                    <h6 class="mb-1">${facility.title}</h6>
                    <p class="mb-1 small text-muted">${facility.category_name}</p>
                    <p class="mb-0 small">${facility.town || ''}, ${facility.postcode || ''}</p>
                    ${facility.distance ? `<p class="mb-0 small text-success">${parseFloat(facility.distance).toFixed(1)} km away</p>` : ''}
                </div>
            `;

            // Add click event listener
            facilityElement.addEventListener('click', () => {
                this.highlightFacility(facility.id);
            });

            fragment.appendChild(facilityElement);
        });

        // Remove sentinel before appending new items
        if (sentinel && sentinel.parentNode) {
            sentinel.parentNode.removeChild(sentinel);
        }

        // Append all facilities at once
        container.appendChild(fragment);

        // Add sentinel back at the end
        if (sentinel) {
            container.appendChild(sentinel);
        }
    }

    // Set up event handlers
    setupEventHandlers() {
        // Handle add status button clicks in popups
        document.addEventListener('click', (e) => {
            // Handle add status button clicks in popups
            if (e.target.classList.contains('add-status-btn') || e.target.closest('.add-status-btn')) {
                const button = e.target.classList.contains('add-status-btn') ? e.target : e.target.closest('.add-status-btn');
                const facilityId = button.getAttribute('data-facility-id');
                if (facilityId) {
                    this.loadFacilityStatuses(facilityId);
                }
            }

            // Handle edit status button clicks
            if (e.target.classList.contains('edit-status-btn') || e.target.closest('.edit-status-btn')) {
                const button = e.target.classList.contains('edit-status-btn') ? e.target : e.target.closest('.edit-status-btn');
                const statusId = button.getAttribute('data-status-id');
                const comment = button.getAttribute('data-comment');
                if (statusId && comment) {
                    this.showEditStatusForm(statusId, comment);
                }
            }

            // Handle cancel edit button
            if (e.target.classList.contains('cancel-edit-btn')) {
                const facilityId = document.getElementById('facility-id').value;
                if (facilityId) {
                    this.loadFacilityStatuses(facilityId);
                }
            }
        });

        // Handle form submissions
        document.addEventListener('submit', (e) => {
            // Edit status form
            if (e.target.classList.contains('edit-status-form')) {
                e.preventDefault();

                const statusId = e.target.querySelector('[name="status-id"]').value;
                const comment = e.target.querySelector('[name="comment"]').value;

                if (statusId && comment) {
                    this.updateStatus(statusId, comment);
                }
            }
        });
    }

    // Load statuses for a facility
    loadFacilityStatuses(facilityId) {
        const url = `${window.SITE_URL}/api/status/get/${facilityId}`;
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(statuses => {
                // Display statuses in a modal
                this.showStatusesModal(facilityId, statuses);
            })
            .catch(error => {
                console.error('Error loading statuses:', error);
                this.showNotification('Error loading status updates', 'danger');
            });
    }

    showStatusesModal(facilityId, statuses) {
        // Get the facility data
        const facility = this.facilities.find(f => f.id == facilityId);
        if (!facility) return;

        // Get the existing modal
        let modal = document.getElementById('statusModal');

        let bootstrapModal = bootstrap.Modal.getInstance(modal);
        if (bootstrapModal) {
            // If a modal instance already exists, dispose it properly first
            bootstrapModal.dispose();
        }

        // Update modal content
        const modalBody = document.getElementById('status-modal-body');
        modalBody.innerHTML = `
        <h5>${facility.title}</h5>
        <p class="text-muted">${facility.category_name}</p>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <h6>Add Status Update</h6>
                <form id="add-status-form">
                    <input type="hidden" id="facility-id" value="${facilityId}">
                    <div class="mb-3">
                        <textarea class="form-control" id="status-comment" rows="3" required
                                placeholder="e.g., 'Not working', 'Bin is full', 'All equipment operational'"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Submit</button>
                </form>
            </div>
            
            <div class="col-md-6">
                <h6>Recent Status Updates</h6>
                <div id="status-list" class="status-list">
                    ${statuses.length > 0 ?
            statuses.map(status => this.renderStatusItem(status)).join('') :
            '<p class="text-muted">No status updates yet.</p>'
        }
                </div>
            </div>
        </div>
    `;

        // Create a new Bootstrap modal instance and show it
        bootstrapModal = new bootstrap.Modal(modal, {
            backdrop: 'static', // Prevents closing when clicking outside
            keyboard: true      // Allows ESC key to close
        });
        bootstrapModal.show();

        // Set up a one-time event listener for when the modal is hidden
        modal.addEventListener('hidden.bs.modal', () => {
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }

            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';

            // Dispose of the modal to clean up event listeners
            if (bootstrapModal) {
                bootstrapModal.dispose();
            }
        }, { once: true });

        // Add event listeners for forms
        this.setupStatusFormListeners();
    }

    setupStatusFormListeners() {
        const addForm = document.getElementById('add-status-form');
        if (addForm) {
            const newForm = addForm.cloneNode(true);
            addForm.parentNode.replaceChild(newForm, addForm);

            newForm.addEventListener('submit', (e) => {
                e.preventDefault();

                const facilityId = document.getElementById('facility-id').value;
                const comment = document.getElementById('status-comment').value;

                if (facilityId && comment) {
                    this.submitStatus(facilityId, comment);
                }
            });
        }

        // Edit status buttons
        const editButtons = document.querySelectorAll('.edit-status-btn');
        editButtons.forEach(button => {
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);

            newButton.addEventListener('click', () => {
                const statusId = newButton.getAttribute('data-status-id');
                const comment = newButton.getAttribute('data-comment');
                this.showEditStatusForm(statusId, comment);
            });
        });
    }

    showEditStatusForm(statusId, comment) {
        const statusItem = document.querySelector(`.status-item[data-status-id="${statusId}"]`);
        if (!statusItem) return;

        // Replace the content with an edit form
        statusItem.innerHTML = `
        <div class="card-body p-3">
            <form id="edit-status-form-${statusId}" class="edit-status-form">
                <input type="hidden" name="status-id" value="${statusId}">
                <div class="mb-2">
                    <textarea class="form-control" name="comment" rows="2" required>${comment}</textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-secondary me-2 cancel-edit-btn">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success">Update</button>
                </div>
            </form>
        </div>
    `;

        // Add event listeners
        const form = statusItem.querySelector('.edit-status-form');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formStatusId = form.querySelector('[name="status-id"]').value;
            const formComment = form.querySelector('[name="comment"]').value;
            this.updateStatus(formStatusId, formComment);
        });

        const cancelBtn = statusItem.querySelector('.cancel-edit-btn');
        cancelBtn.addEventListener('click', () => {
            // Reload statuses to refresh the view
            const facilityId = document.getElementById('facility-id').value;
            this.loadFacilityStatuses(facilityId);
        });
    }

    renderStatusItem(status) {
        const canEdit = window.currentUserId && status.userId == window.currentUserId;
        return `
        <div class="card mb-2 status-item" data-status-id="${status.id}">
            <div class="card-body p-3">
                <p class="mb-1">${status.statuscomment}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">By: ${status.username}</small>
                    ${canEdit ? `
                        <button type="button" class="btn btn-sm btn-outline-primary edit-status-btn"
                            data-status-id="${status.id}" 
                            data-comment="${status.statuscomment.replace(/"/g, '&quot;')}">
                            Edit
                        </button>
                    ` : ''}
                </div>
            </div>
        </div>
    `;
    }

    // Submit new status update
    submitStatus(facilityId, comment) {
        const formData = new FormData();
        formData.append('facilityId', facilityId);
        formData.append('comment', comment);

        fetch(`${window.SITE_URL}/api/status/add`, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Clear the form
                    document.getElementById('status-comment').value = '';

                    // Show success message
                    this.showNotification('Status update added successfully', 'success');

                    // Reload the statuses
                    this.loadFacilityStatuses(facilityId);
                } else {
                    throw new Error(data.message || 'Error adding status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.showNotification('Error: ' + error.message, 'danger');
            });
    }

    updateStatus(statusId, comment) {
        const formData = new FormData();
        formData.append('statusId', statusId);
        formData.append('comment', comment);

        fetch(`${window.SITE_URL}/api/status/update`, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success message
                    this.showNotification('Status update edited successfully', 'success');

                    // Reload the statuses
                    const facilityId = document.getElementById('facility-id').value;
                    this.loadFacilityStatuses(facilityId);
                } else {
                    throw new Error(data.message || 'Error updating status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.showNotification('Error: ' + error.message, 'danger');
            });
    }

    // Show notification
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show notification-toast`;
        notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

        document.body.appendChild(notification);

        // Auto-remove after 2.5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentNode) {
                        document.body.removeChild(notification);
                    }
                }, 500);
            }
        }, 2500);
    }

    // Highlight facility on the map
    highlightFacility(facilityId) {
        const facilityMarker = this.markers.find(marker =>
            marker.facility.id.toString() === facilityId.toString());

        if (facilityMarker) {
            // Pan map to marker
            this.map.setView(facilityMarker.getLatLng(), 15);

            // Open popup
            facilityMarker.openPopup();

            // Highlight in list
            this.highlightFacilityInList(facilityId);
        }
    }

    // Highlight facility in list
    highlightFacilityInList(facilityId) {
        // Remove highlight from all items
        const allItems = document.querySelectorAll('.facility-item');
        allItems.forEach(item => {
            item.classList.remove('active', 'bg-light');
        });

        // Add highlight to selected item
        const selectedItem = document.querySelector(`.facility-item[data-facility-id="${facilityId}"]`);
        if (selectedItem) {
            selectedItem.classList.add('active', 'bg-light');
            selectedItem.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

    // Show error message
    showError(message) {
        const mapContainer = document.getElementById(this.mapContainerId);
        if (!mapContainer) return;

        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-danger';
        errorDiv.textContent = message;

        mapContainer.innerHTML = '';
        mapContainer.appendChild(errorDiv);
    }
}