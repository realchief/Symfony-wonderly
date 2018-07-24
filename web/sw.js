var CACHE_NAME = 'wonderly-cache-v1';
var urlsToCache = [
    'assetic/commonJS.js',
    'assetic/homepageJS.js',
    'assetic/commonCSS.css',
    'assetic/homepageCSS.css'
];

self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function(cache) {
                console.log('Opened cache');
                return cache.addAll(urlsToCache);
            })
    );
});

self.addEventListener('fetch', function(e){

});