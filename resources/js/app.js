import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);

/** Live market ticker: hydrates from server data, refreshes quietly every 2 minutes. */
Alpine.data('ticker', (initial) => ({
    state: initial, // {assets, updated_at, stale} | null
    loading: false,
    init() { setInterval(() => this.refresh(), 120000); },
    async refresh() {
        this.loading = true;
        try {
            const r = await fetch('/api/market', { headers: { Accept: 'application/json' } });
            if (r.ok) { const d = await r.json(); this.state = d.data; }
        } catch (e) { /* keep last state */ }
        this.loading = false;
    },
    price(p) {
        const d = p >= 100 ? 0 : (p >= 1.1 ? 2 : 4);
        return '$' + p.toLocaleString('en-US', { minimumFractionDigits: d, maximumFractionDigits: d });
    },
    pct(c) { return c == null ? '—' : (c >= 0 ? '+' : '') + c.toFixed(2) + '%'; },
    updated() {
        if (!this.state) return '';
        return new Date(this.state.updated_at * 1000).toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', timeZone: 'UTC' }) + ' UTC';
    },
}));

/** Reading progress bar for articles. */
Alpine.data('readingProgress', () => ({
    pct: 0,
    init() {
        const el = document.getElementById('article-body');
        const calc = () => {
            if (!el) return;
            const r = el.getBoundingClientRect();
            const total = r.height - window.innerHeight * 0.6;
            this.pct = Math.min(100, Math.max(0, (-r.top / total) * 100));
        };
        window.addEventListener('scroll', calc, { passive: true });
        calc();
    },
}));

/** Newsletter form with inline feedback (works without JS too). */
Alpine.data('newsletter', (source = 'site') => ({
    email: '', status: 'idle', message: '',
    async submit(e) {
        this.status = 'loading';
        try {
            const r = await fetch('/newsletter', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                body: JSON.stringify({ email: this.email, source }),
            });
            const d = await r.json();
            this.status = r.ok ? 'ok' : 'error';
            this.message = d.message || (d.errors ? Object.values(d.errors)[0][0] : 'Something went wrong.');
            if (r.ok) this.email = '';
        } catch (err) { this.status = 'error'; this.message = 'Network error. Please try again.'; }
    },
}));

Alpine.data('copyLink', () => ({
    done: false,
    async copy(url) {
        try { await navigator.clipboard.writeText(url); } catch (e) { /* ignore */ }
        this.done = true; setTimeout(() => (this.done = false), 1800);
    },
}));

window.Alpine = Alpine;
Alpine.start();
