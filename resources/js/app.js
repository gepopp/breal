import {Loader} from "@googlemaps/js-api-loader"
window.loader = new Loader({
    apiKey: "AIzaSyCHGK2awBTZjdOELEp1Phs1adi5r0xhnY4",
    version: "weekly",
    libraries: ['marker'],
});

import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
window.Swiper = Swiper;

import Fuse from 'fuse.js';
window.Fuse = Fuse;


import.meta.glob([
    '../fonts/**',
]);

import AOS from 'aos';
import 'aos/dist/aos.css'; // You can also use <link> for styles

AOS.init({
    // Global settings:
    disable: false, // accepts following values: 'phone', 'tablet', 'mobile', boolean, expression or function
    startEvent: 'DOMContentLoaded', // name of the event dispatched on the document, that AOS should initialize on
    initClassName: 'aos-init', // class applied after initialization
    animatedClassName: 'aos-animate', // class applied on animation
    useClassNames: false, // if true, will add content of `data-aos` as classes on scroll
    disableMutationObserver: false, // disables automatic mutations' detections (advanced)
    debounceDelay: 50, // the delay on debounce used while resizing window (advanced)
    throttleDelay: 99, // the delay on throttle used while scrolling the page (advanced)
    // Settings that can be overridden on per-element basis, by `data-aos-*` attributes:
    offset: 120, // offset (in px) from the original trigger point
    delay: 300, // values from 0 to 3000, with step 50ms
    duration: 800, // values from 0 to 3000, with step 50ms
    easing: 'ease', // default easing for AOS animations
    once: true, // whether animation should happen only once - while scrolling down
    mirror: false, // whether elements should animate out while scrolling past them
    anchorPlacement: 'top-bottom', // defines which position of the element regarding to window should trigger the animation

});

import lottieWeb from "lottie-web";
import elements from "aos/src/js/helpers/elements.js";
window.lottie = lottieWeb;

document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.button-hover');

    buttons.forEach(button => {
        button.addEventListener('mouseenter', handleMouseEvent);
        button.addEventListener('mouseout', handleMouseEvent);
    });

    function handleMouseEvent(e) {
        const button = e.currentTarget;
        const span = button.querySelector('span');

        const buttonRect = button.getBoundingClientRect();
        const relX = e.clientX - buttonRect.left;
        const relY = e.clientY - buttonRect.top;

        span.style.top = `${relY}px`;
        span.style.left = `${relX}px`;
    }
});


import SimpleParallax from "simple-parallax-js/vanilla";
window.SimpleParallax = SimpleParallax;


/**
 * Autoplaying carousels need a keyboard-operable pause button (WCAG 2.2.2).
 * Sliders that bring their own control are marked with data-pause-control; data-pause-position="right"
 * moves the button to the right for sliders with a decorative overlay at the bottom left.
 */
const addCarouselPauseControls = () => {
    document.querySelectorAll('.swiper').forEach((element) => {
        const swiper = element.swiper;

        if (!swiper || !swiper.params.autoplay || element.dataset.pauseControl) {
            return;
        }

        element.dataset.pauseControl = 'generic';

        const pauseLabel = document.body.dataset.carouselPause;
        const playLabel = document.body.dataset.carouselPlay;
        const icons = {
            pause: '<svg aria-hidden="true" class="size-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6 5h4v14H6zM14 5h4v14h-4z"/></svg>',
            play: '<svg aria-hidden="true" class="size-4" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>',
        };
        const button = document.createElement('button');

        button.type = 'button';
        button.className = 'absolute bottom-4 ' + (element.dataset.pausePosition === 'right' ? 'right-4' : 'left-4') + ' z-[10000] size-10 rounded-full bg-white/90 text-logo-950 shadow flex items-center justify-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-logo-500';

        const render = (playing) => {
            button.innerHTML = playing ? icons.pause : icons.play;
            button.setAttribute('aria-label', playing ? pauseLabel : playLabel);
        };

        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            swiper.autoplay.stop();
        }

        render(swiper.autoplay.running);

        button.addEventListener('click', () => {
            swiper.autoplay.running ? swiper.autoplay.stop() : swiper.autoplay.start();
            render(swiper.autoplay.running);
        });

        element.appendChild(button);
    });
};

const scheduleCarouselPauseControls = () => {
    let attempts = 0;
    const timer = setInterval(() => {
        addCarouselPauseControls();

        if (++attempts >= 10) {
            clearInterval(timer);
        }
    }, 500);
};

document.addEventListener('DOMContentLoaded', scheduleCarouselPauseControls);
document.addEventListener('livewire:navigated', scheduleCarouselPauseControls);


/**
 * The service page switches its tab without a page load, so the language links
 * rendered with the page would point to an outdated tab: take it from the address bar.
 */
document.addEventListener('click', (event) => {
    const link = event.target.closest('a[data-locale-switch]');

    if (!link) {
        return;
    }

    const tab = new URLSearchParams(window.location.search).get('tab');
    const onServicePage = window.location.pathname.replace(/^\/en(?=\/|$)/, '') === '/service';
    const target = new URL(link.href);

    if (onServicePage && tab && /^[a-z0-9-]+$/.test(tab)) {
        target.searchParams.set('tab', tab);
    } else {
        target.searchParams.delete('tab');
    }

    link.href = target.toString();
});


/**
 * Image lightbox of the property pages and the property list (Spotlight.js 0.7.8,
 * Apache-2.0, self-hosted). Loaded as its own chunk, only where a gallery exists.
 */
const loadSpotlight = () => {
    if (!window.Spotlight && document.querySelector('.spotlight')) {
        import('./spotlight.js');
    }
};

document.addEventListener('DOMContentLoaded', loadSpotlight);
document.addEventListener('livewire:navigated', loadSpotlight);
