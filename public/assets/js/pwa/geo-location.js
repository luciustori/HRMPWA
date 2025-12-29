// public/assets/js/pwa/geo-location.js

const GeoLocation = {
    OFFICE_LOCATIONS: {
        1: { lat: -7.8275, lng: 110.3794, radius: 100 } // Yogya HQ
    },

    getOfficeLocation(officeId = 1) {
        return this.OFFICE_LOCATIONS[officeId] || this.OFFICE_LOCATIONS;
    },

    distance(lat1, lon1, lat2, lon2) {
        // Haversine formula
        const R = 6371; // km
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = 
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c * 1000; // meters
    },

    async checkWithinOffice(officeId = 1) {
        try {
            const pos = await new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(resolve, reject);
            });

            const office = this.getOfficeLocation(officeId);
            const dist = this.distance(
                pos.coords.latitude,
                pos.coords.longitude,
                office.lat,
                office.lng
            );

            return {
                withinRadius: dist <= office.radius,
                distance: dist.toFixed(2),
                office: office
            };
        } catch (err) {
            throw new Error('Geolocation failed: ' + err.message);
        }
    }
};
