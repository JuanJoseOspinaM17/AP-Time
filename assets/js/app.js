document.addEventListener('DOMContentLoaded', () => {
    const thumbnails = document.querySelectorAll('.thumb');
    const mainImage = document.getElementById('mainProductImage');
    const menuButton = document.querySelector('.menu-toggle');
    const nav = document.querySelector('.header-inner nav');

    thumbnails.forEach((thumbnail) => {
        thumbnail.addEventListener('click', () => {
            if (mainImage) {
                mainImage.src = thumbnail.dataset.image;
            }

            thumbnails.forEach((item) => {
                item.classList.remove('selected');
            });

            thumbnail.classList.add('selected');
        });
    });

    if (menuButton && nav) {
        menuButton.addEventListener('click', () => {
            nav.classList.toggle('mobile-open');
        });
    }
});
