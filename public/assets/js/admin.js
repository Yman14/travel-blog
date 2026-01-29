//render feature image uploaded
(function () {
    const featureInput = document.getElementById('featureInput');
    const preview = document.getElementById('featurePreview');

    if (!featureInput || !preview) return;

    featureInput.addEventListener('change', function (e) {
        preview.innerHTML = '';
        Array.from(e.target.files).forEach(file => {
            if (!file.type.startsWith('image/')) return;

            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.classList.add("post-featured");
            preview.appendChild(img);
        });
    });
})();


//render gallery images uploaded
(function () {
    const input = document.getElementById('galleryInput');
    const preview = document.getElementById('galleryPreview');

    if (!input || !preview) return;

    let files = [];

    input.addEventListener('change', () => {
        for (const file of input.files) {
            if (!file.type.startsWith('image/')) continue;
            files.push(file);
        }
        render();
        syncInput();
    });

    function render() {
        preview.innerHTML = '';

        files.forEach((file, index) => {
            const wrapper = document.createElement('div');
            wrapper.className = 'media-item';

            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);

            const btn = document.createElement('button');
            btn.className = 'media-remove';
            btn.innerHTML = '×';
            btn.onclick = () => {
                files.splice(index, 1);
                render();
                syncInput();
            };

            wrapper.appendChild(img);
            wrapper.appendChild(btn);
            preview.appendChild(wrapper);
        });
    }

    function syncInput() {
        const dt = new DataTransfer();
        files.forEach(f => dt.items.add(f));
        input.files = dt.files;
    }
})();


//darken if image is check tobe remove
document.querySelectorAll('.image-remove input').forEach(cb => {
    cb.addEventListener('change', e => {
        const img = e.target.closest('li').querySelector('img');
        img.style.opacity = e.target.checked ? '0.4' : '1';
        img.style.filter = e.target.checked ? 'grayscale(100%)' : 'none';
    });
});