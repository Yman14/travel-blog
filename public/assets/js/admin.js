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
    const form = document.getElementById('form');

    if (!form || !input || !preview) return;

    // id, file/HTMLElement
    const filesMap = new Map();
    const nodesMap = new Map();

    input.addEventListener('change', async () => {
        for (const file of input.files) {
            if (!file.type.startsWith('image/')) continue;
            
            //create id for each file for easy tracking using map
            const id = crypto.randomUUID();

            //set the file and htmlelement
            filesMap.set(id, file);
            await addNode(id, file);
        }
        //syncInput();
    });

    //reset input value so same image can be re select 
    input.addEventListener('click', () => {
        input.value = null;
    });

    //only update the input files if the form is being submit
    form.addEventListener('submit', () => {
        const dt = new DataTransfer();
        for (const file of filesMap.values()) {
            dt.items.add(file);
        }
        input.files = dt.files;
    });

    async function addNode(id, file) {
        const wrapper = document.createElement('div');
        wrapper.className = 'media-item';
        wrapper.dataset.id = id;

        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        try { await img.decode(); } catch {}
        URL.revokeObjectURL(img.src);

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'media-remove';
        btn.textContent = '×';

        btn.addEventListener('click', () => removeNode(id));

        wrapper.appendChild(img);
        wrapper.appendChild(btn);

        preview.appendChild(wrapper);
        nodesMap.set(id, wrapper);
    }

    function removeNode(id) {
        const node = nodesMap.get(id);
        if (!node) return;

        const img = node.querySelector('img');
        // URL.revokeObjectURL(img.src);

        node.remove();
        nodesMap.delete(id);
        filesMap.delete(id);

        //syncInput();
    }

    function syncInput() {
        const dt = new DataTransfer();
        for (const file of filesMap.values()) {
            dt.items.add(file);
        }
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