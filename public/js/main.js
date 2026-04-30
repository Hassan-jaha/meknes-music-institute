// public/js/main.js

document.addEventListener('DOMContentLoaded', () => {
    // Lightbox Simple
    const galleryItems = document.querySelectorAll('.gallery-item');
    if (galleryItems.length > 0) {
        // Création du conteneur lightbox
        const lightbox = document.createElement('div');
        lightbox.id = 'lightbox';
        lightbox.style.cssText = `
            position: fixed;
            z-index: 2000;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.9);
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        `;
        
        const lightboxImg = document.createElement('img');
        lightboxImg.style.cssText = 'max-width: 90%; max-height: 90%; box-shadow: 0 0 20px rgba(0,0,0,0.5); border: 5px solid white;';
        lightbox.appendChild(lightboxImg);
        document.body.appendChild(lightbox);

        galleryItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const fullSrc = item.getAttribute('href');
                lightboxImg.src = fullSrc;
                lightbox.style.display = 'flex';
            });
        });

        lightbox.addEventListener('click', () => {
            lightbox.style.display = 'none';
        });
    }
});
