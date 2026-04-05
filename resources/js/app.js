import './bootstrap';


document.addEventListener('alpine:init', () => {
    Alpine.data('timer', (total) => ({
        seconds: 0,
        total: total,
        get display() {
            const m = Math.floor(this.seconds / 60);
            const s = this.seconds % 60;
            return m > 0 ? `${m}:${String(s).padStart(2, '0')}` : `${s}`;
        },
        get pct() {
            return Math.min((this.seconds / this.total) * 100, 100);
        },
        init() {
            setInterval(() => { this.seconds++; }, 1000);
        },
    }));

    // Alpine.data('swipeHandler', () => ({
    //     startX: 0,
    //     startY: 0,
    //     startTouch(e) {
    //         this.startX = e.touches[0].clientX;
    //         this.startY = e.touches[0].clientY;
    //     },
    //     endTouch(e) {
    //         const dx = e.changedTouches[0].clientX - this.startX;
    //         const dy = e.changedTouches[0].clientY - this.startY;
    //         if (Math.abs(dx) > 60 && Math.abs(dx) > Math.abs(dy)) {
    //             this.$dispatch('card-completed');
    //         }
    //     },
    // }));


    Alpine.data('swipeHandler', () => ({
        startX: 0,
        startY: 0,
        startTime: 0,

        init() {
            const el = this.$el;

            // Pointer events (werkt beter in Android WebView)
            el.addEventListener('pointerdown', (e) => {
                this.startX = e.clientX;
                this.startY = e.clientY;
                this.startTime = Date.now();
                el.setPointerCapture(e.pointerId);
            }, { passive: true });

            el.addEventListener('pointerup', (e) => {
                const dx = e.clientX - this.startX;
                const dy = e.clientY - this.startY;
                const dt = Date.now() - this.startTime;

                // Horizontale swipe: min 50px, sneller dan 500ms, 
                // meer horizontaal dan verticaal
                if (
                    Math.abs(dx) > 50 &&
                    Math.abs(dx) > Math.abs(dy) * 1.5 &&
                    dt < 500
                ) {
                    this.$dispatch('card-completed');
                }
            }, { passive: true });

            // Fallback: touch events voor oudere WebViews
            el.addEventListener('touchstart', (e) => {
                this.startX = e.touches[0].clientX;
                this.startY = e.touches[0].clientY;
                this.startTime = Date.now();
            }, { passive: true });

            el.addEventListener('touchend', (e) => {
                const dx = e.changedTouches[0].clientX - this.startX;
                const dy = e.changedTouches[0].clientY - this.startY;
                const dt = Date.now() - this.startTime;

                if (
                    Math.abs(dx) > 50 &&
                    Math.abs(dx) > Math.abs(dy) * 1.5 &&
                    dt < 500
                ) {
                    this.$dispatch('card-completed');
                }
            }, { passive: true });
        },

        // Lege methodes zodat x-on: attributes niet crashen
        startTouch() {},
        endTouch() {},
    }));
});
