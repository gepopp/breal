import {Loader} from "@googlemaps/js-api-loader"
window.loader = new Loader({
    apiKey: "AIzaSyCHGK2awBTZjdOELEp1Phs1adi5r0xhnY4",
    version: "weekly",
    libraries: ['marker'],
});

import Swiper from 'swiper/bundle';
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