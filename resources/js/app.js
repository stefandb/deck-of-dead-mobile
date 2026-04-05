import './bootstrap';


document.addEventListener('alpine:init', () => {
    Alpine.data('timer', (total) => ({
        seconds: total,
        total: total,
        running: false,
        finished: false,
        interval: null,
        circumference: 2 * Math.PI * 80,

        get display() {
            return `${this.seconds}`;
        },
        get pct() {
            return this.total > 0 ? this.seconds / this.total : 0;
        },
        get dashoffset() {
            return this.circumference * (1 - this.pct);
        },
        get color() {
            const hue = Math.round((1 - this.pct) * 120);
            return `hsl(${hue}, 80%, 55%)`;
        },

        start() {
            if (this.running || this.finished) return;
            this.running = true;
            this.$dispatch('timer-lock');
            this.interval = setInterval(() => {
                if (this.seconds > 0) {
                    this.seconds--;
                }
                if (this.seconds === 0) {
                    clearInterval(this.interval);
                    this.running = false;
                    this.finished = true;
                    this.$dispatch('timer-unlock');
                }
            }, 1000);
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
        swipeLocked: false,
        startedOnCard: false,

        init() {
            const el = this.$el;

            el.addEventListener('pointerdown', (e) => {
                this.startedOnCard = !!e.target.closest('[data-swipe-zone]');
                this.startX = e.clientX;
                this.startY = e.clientY;
                this.startTime = Date.now();
                el.setPointerCapture(e.pointerId);
            }, { passive: true });

            el.addEventListener('pointerup', (e) => {
                if (this.swipeLocked || !this.startedOnCard) return;
                const dx = e.clientX - this.startX;
                const dy = e.clientY - this.startY;
                const dt = Date.now() - this.startTime;

                if (
                    Math.abs(dx) > 50 &&
                    Math.abs(dx) > Math.abs(dy) * 1.5 &&
                    dt < 500
                ) {
                    this.$dispatch('card-completed');
                }
            }, { passive: true });

            el.addEventListener('touchstart', (e) => {
                this.startedOnCard = !!e.target.closest('[data-swipe-zone]');
                this.startX = e.touches[0].clientX;
                this.startY = e.touches[0].clientY;
                this.startTime = Date.now();
            }, { passive: true });

            el.addEventListener('touchend', (e) => {
                if (this.swipeLocked || !this.startedOnCard) return;
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

        startTouch() {},
        endTouch() {},
    }));
});
