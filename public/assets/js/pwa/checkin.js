// public/assets/js/pwa/checkin.js

const CheckIn = {
    async getLocation() {
        return new Promise((resolve, reject) => {
            if (!navigator.geolocation) {
                return reject('Geolocation tidak didukung');
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    resolve({
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                        accuracy: position.coords.accuracy
                    });
                },
                (error) => reject(error.message),
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        });
    },

    async takePhoto() {
        return new Promise((resolve, reject) => {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.capture = 'environment';

            input.addEventListener('change', (e) => {
                const file = e.target.files;
                if (!file) return reject('No file selected');

                const reader = new FileReader();
                reader.onload = (event) => {
                    resolve(event.target.result);
                };
                reader.readAsDataURL(file);
            });

            input.click();
        });
    },

    async doCheckIn() {
        const btn = document.getElementById('btn-checkin');
        if (!btn) return;

        btn.disabled = true;
        btn.textContent = '⏳ Loading...';

        try {
            // Get location
            const location = await this.getLocation();

            // Optional: Take photo
            let photo = null;
            if (confirm('Ambil foto untuk check-in?')) {
                photo = await this.takePhoto();
            }

            // Send to server
            const response = await fetch('/api/pwa/checkin', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    latitude: location.latitude,
                    longitude: location.longitude,
                    photo: photo
                })
            });

            const data = await response.json();

            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
                btn.disabled = false;
                btn.textContent = '📍 Check-In';
            }
        } catch (error) {
            alert('Check-in gagal: ' + error);
            btn.disabled = false;
            btn.textContent = '📍 Check-In';
        }
    },

    async doCheckOut() {
        const btn = document.getElementById('btn-checkout');
        if (!btn) return;

        btn.disabled = true;
        btn.textContent = '⏳ Loading...';

        try {
            const location = await this.getLocation();
            let photo = null;

            if (confirm('Ambil foto untuk check-out?')) {
                photo = await this.takePhoto();
            }

            const response = await fetch('/api/pwa/checkout', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    latitude: location.latitude,
                    longitude: location.longitude,
                    photo: photo
                })
            });

            const data = await response.json();

            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
                btn.disabled = false;
                btn.textContent = '🚪 Check-Out';
            }
        } catch (error) {
            alert('Check-out gagal: ' + error);
            btn.disabled = false;
            btn.textContent = '🚪 Check-Out';
        }
    }
};

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    const btnIn = document.getElementById('btn-checkin');
    const btnOut = document.getElementById('btn-checkout');

    if (btnIn) {
        btnIn.addEventListener('click', () => CheckIn.doCheckIn());
    }

    if (btnOut) {
        btnOut.addEventListener('click', () => CheckIn.doCheckOut());
    }
});
