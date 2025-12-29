// public/service-worker.js

const CACHE_NAME = 'hrm-pwa-v1';
const STATIC_ASSETS = [
  '/',
  '/pwa/dashboard',
  '/pwa/checkin',
  '/pwa/requests',
  '/pwa/profile',
  '/assets/css/tailwind.css',
  '/assets/css/pwa-theme.css',
  '/assets/js/pwa/checkin.js',
  '/assets/js/pwa/requests.js',
  '/assets/js/pwa/geo-location.js',
];

// Install event
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(STATIC_ASSETS))
      .then(() => self.skipWaiting())
  );
});

// Activate event
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

// Fetch event - Network first for API, Cache first for assets
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);

  // API requests - network first
  if (url.pathname.startsWith('/api/')) {
    event.respondWith(
      fetch(request)
        .then(response => {
          if (response.ok) {
            const cache = caches.open(CACHE_NAME);
            cache.then(c => c.put(request, response.clone()));
          }
          return response;
        })
        .catch(() => caches.match(request))
    );
  }
  // Assets - cache first
  else {
    event.respondWith(
      caches.match(request)
        .then(response => response || fetch(request))
    );
  }
});

// Background sync untuk pending requests
self.addEventListener('sync', (event) => {
  if (event.tag === 'sync-requests') {
    event.waitUntil(syncPendingRequests());
  }
});

async function syncPendingRequests() {
  const db = await openIndexedDB();
  const pending = await db.getAllFromIndex('requests', 'status', 'pending');
  
  for (const req of pending) {
    try {
      await fetch('/api/pwa/requests/store', {
        method: 'POST',
        body: JSON.stringify(req),
      });
      await db.delete('requests', req.id);
    } catch (err) {
      console.log('Sync failed, will retry later', err);
    }
  }
}
