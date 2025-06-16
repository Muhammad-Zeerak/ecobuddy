// Class representing a facility marker on the map
class FacilityMarker {
    constructor(facility, map) {
        this.facility = facility;
        this.map = map;
        this.marker = null;
        this.popup = null;
        this.createMarker();
    }

    // Create a new marker with the facility data
    createMarker() {
        // Create marker
        this.marker = L.marker(
            [parseFloat(this.facility.lat), parseFloat(this.facility.lng)],
            { title: this.facility.title }
        );

        // Create popup
        this.popup = L.popup({
            minWidth: 250
        }).setContent(this.generatePopupContent());
        this.marker.bindPopup(this.popup);

        // Add marker to map
        this.marker.addTo(this.map);

        // Add click event
        this.marker.on('click', () => {
            this.openPopup();

            // Highlight in the list
            if (this.map.mapController) {
                this.map.mapController.highlightFacilityInList(this.facility.id);
            }
        });
    }

    // Generate HTML content for the popup
    generatePopupContent() {
        return `
        <div class="popup-content">
            <h5 class="mb-2">${this.facility.title}</h5>
            <p class="mb-1"><strong>Category:</strong> ${this.facility.category_name}</p>
            <p class="mb-2">${this.facility.description}</p>
            <p class="mb-3">
                <small>
                    ${this.facility.housenumber || ''} 
                    ${this.facility.streetname || ''}, 
                    ${this.facility.town || ''}, 
                    ${this.facility.postcode || ''}
                </small>
            </p>
            <div class="popup-buttons" style="display: flex; gap: 10px; margin-top: 10px;">
                <a href="${window.SITE_URL}/ecofacilities/view/${this.facility.id}" 
                   class="btn btn-primary btn-popup" 
                   style="color: white !important; display: inline-block; text-decoration: none;">View Details</a>
                ${this.isUserLoggedIn() ?
            `<button class="btn btn-outline-secondary btn-popup add-status-btn" 
                     data-facility-id="${this.facility.id}"
                     style="display: inline-block;">
                        Add Status
                    </button>` :
            ''
        }
            </div>
        </div>
    `;
    }

    // Check if user is logged in
    isUserLoggedIn() {
        return window.isUserLoggedIn === true;
    }

    // Open popup
    openPopup() {
        this.marker.openPopup();
    }

    // Close popup
    closePopup() {
        this.marker.closePopup();
    }

    // Update the marker's content
    updateContent(facility) {
        this.facility = facility;
        this.popup.setContent(this.generatePopupContent());
    }

    // Set marker visibility
    setVisible(visible) {
        if (visible) {
            if (!this.map.hasLayer(this.marker)) {
                this.marker.addTo(this.map);
            }
        } else {
            if (this.map.hasLayer(this.marker)) {
                this.map.removeLayer(this.marker);
            }
        }
    }

    // Get marker position for map positioning
    getLatLng() {
        return this.marker.getLatLng();
    }
}