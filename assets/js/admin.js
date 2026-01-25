(function () {
    const galleryInput = document.getElementById('galleryInput');
    const preview = document.getElementById('galleryPreview');

    if (!galleryInput || !preview) return;

    galleryInput.addEventListener('change', function (e) {
        preview.innerHTML = '';
        Array.from(e.target.files).forEach(file => {
            if (!file.type.startsWith('image/')) return;

            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.style.maxWidth = '120px';
            img.style.margin = '6px';
            preview.appendChild(img);
        });
    });
})();