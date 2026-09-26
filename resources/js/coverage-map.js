// Public coverage map (Leaflet + OpenStreetMap tiles; free, no API key, attribution required).
// Markers come from CoverageArea::mapPoints(): one per region, or an exact pin when an area has coordinates.
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const COLORS = { available: '#1E57D6', coming_soon: '#F59E0B' };
const LABELS = { available: 'Available', coming_soon: 'Coming soon' };

const escape = (value) => String(value).replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c]);

export function mountCoverageMap(el) {
    const points = JSON.parse(el.dataset.points || '[]');
    if (!points.length) return;

    const map = L.map(el, {
        scrollWheelZoom: false, // don't hijack page scrolling; zoom with buttons, pinch or ctrl+scroll
        attributionControl: true,
    });

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    const bounds = [];
    points.forEach((p) => {
        const color = COLORS[p.status] || COLORS.coming_soon;
        const places = p.places?.length ? `<p style="margin:.35rem 0 0;color:#64748b">${p.places.map(escape).join('<br>')}</p>` : '';
        const url = new URL(el.dataset.checkUrl, window.location.origin);
        url.searchParams.set('region', p.region);
        url.hash = 'check';

        L.circleMarker([p.lat, p.lng], {
            radius: 11,
            color: '#fff',
            weight: 3,
            fillColor: color,
            fillOpacity: 0.95,
        })
            .bindTooltip(escape(p.title), { direction: 'top', offset: [0, -10] })
            .bindPopup(
                `<strong style="font-size:.95rem">${escape(p.title)}</strong>` +
                `<p style="margin:.2rem 0 0;font-weight:600;color:${color}">${LABELS[p.status] || ''}</p>` +
                places +
                `<a href="${url}" style="display:inline-block;margin-top:.6rem;font-weight:600">Check this area →</a>`,
            )
            .addTo(map);

        bounds.push([p.lat, p.lng]);
    });

    const fit = () => map.fitBounds(bounds, { padding: [36, 36], maxZoom: 10 });
    fit();

    // Leaflet measures the container once; re-measure when it changes size (lazy-loaded CSS,
    // responsive layout, rotating a phone) so the tiles and markers stay aligned.
    let lastWidth = el.clientWidth;
    new ResizeObserver(() => {
        map.invalidateSize();
        if (el.clientWidth !== lastWidth) {
            lastWidth = el.clientWidth;
            fit();
        }
    }).observe(el);

    // Ctrl/⌘ + wheel zooms, so the page still scrolls normally over the map.
    el.addEventListener('wheel', (e) => {
        if (e.ctrlKey || e.metaKey) {
            e.preventDefault();
            map.scrollWheelZoom.enable();
        } else {
            map.scrollWheelZoom.disable();
        }
    }, { passive: false });
}
