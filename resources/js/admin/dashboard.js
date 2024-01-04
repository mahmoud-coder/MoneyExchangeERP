import Swiper from 'swiper';
import { Autoplay, Pagination } from 'swiper/modules';

new Swiper('.slides-container', {
    loop:true,
    spaceBetween: 30,
    centeredSlides: true,
    autoplay: {
        delay: 2500,
        disableOnInteraction: false,
    },
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    modules: [Autoplay, Pagination]
})