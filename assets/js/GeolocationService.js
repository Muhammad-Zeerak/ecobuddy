// Singleton service for handling user geolocation
class GeolocationService {
    constructor() {
        this.currentPosition = null;
        this.defaultPosition = { lat: 53.480759, lng: -2.242631 }; // Fallback: Manchester city center
        this.listeners = [];
    }

    // Get current user position
    getUserLocation() {
        return new Promise((resolve, reject) => {
            if (this.currentPosition) {
                resolve(this.currentPosition);
                return;
            }

            if (!navigator.geolocation) {
                console.warn("Geolocation is not supported by this browser.");
                resolve(this.defaultPosition);
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.currentPosition = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    this.notifyListeners();
                    resolve(this.currentPosition);
                },
                (error) => {
                    console.warn(`Geolocation error: ${error.message}`);
                    resolve(this.defaultPosition);
                },
                { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 }
            );
        });
    }

    // Notify all listeners of position change
    notifyListeners() {
        this.listeners.forEach(callback => {
            callback(this.currentPosition);
        });
    }

    // Get instance (Singleton pattern)
    static getInstance() {
        if (!GeolocationService.instance) {
            GeolocationService.instance = new GeolocationService();
        }
        return GeolocationService.instance;
    }
}