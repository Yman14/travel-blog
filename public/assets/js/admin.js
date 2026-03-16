//render feature image uploaded
(function () {
    const featureInput = document.getElementById('featureInput');
    const preview = document.getElementById('featurePreview');

    if (!featureInput || !preview) return;

    let objectUrl = null;

    featureInput.addEventListener('change', () => {
        preview.innerHTML = '';

        const file = featureInput.files[0];
        if (!file || !file.type.startsWith('image/')) {
            featureInput.value = '';
            return;
        }

        const wrapper = document.createElement('div');
        wrapper.className = 'media-item';

        const img = document.createElement('img');
        objectUrl = URL.createObjectURL(file);
        img.src = objectUrl;

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'media-remove';
        btn.setAttribute('aria-label', 'Remove featured image');
        btn.textContent = '×';

        btn.addEventListener('click', () => {
            if (objectUrl) {
                URL.revokeObjectURL(objectUrl);
                objectUrl = null;
            }
            featureInput.value = '';
            preview.innerHTML = '';
        });

        wrapper.appendChild(img);
        wrapper.appendChild(btn);
        preview.appendChild(wrapper);
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
            if (filesMap.size >= 10) {
                alert('Maximum 10 images');
                return;
            }
            
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
document.querySelectorAll('.image-remove').forEach(cb => {
    cb.addEventListener('change', e => {
        const img = e.target.closest('.media-item').querySelector('img');
        img.style.opacity = e.target.checked ? '0.4' : '1';
        img.style.filter = e.target.checked ? 'grayscale(100%)' : 'none';
    });
});


//delete modal in manage posts(posts.php)
(function () {
    const modal = document.getElementById('deleteModal');
    const message = document.getElementById('deleteMessage');
    const postIdInput = document.getElementById('deletePostId');
    const cancelBtn = document.getElementById('cancelDelete');

    if(!modal) return;

    document.querySelectorAll('.btn-delete-trigger').forEach(btn => {
        btn.addEventListener('click', () => {
            postIdInput.value = btn.dataset.id;
            message.textContent = `Are you sure you want to delete “${btn.dataset.title}”?`;
            modal.classList.remove('hidden');
        });
    });

    cancelBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        postIdInput.value = '';
    });
})();


// Check/Uncheck All, count checkbox
(function() {
    const checkAll = document.getElementById('check-all');
    const countDisplay = document.getElementById('selected-count');
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    const panel = document.querySelector('.bulk-action-footer');

    if(!checkAll || !checkboxes || !countDisplay || !panel)  return;

    checkAll.addEventListener('change', function() {
        document.querySelectorAll('.post-checkbox').forEach(cb => cb.checked = this.checked);
    });

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            let checkedCount = document.querySelectorAll('input[type="checkbox"]:checked').length;
            if(checkAll.checked) checkedCount = checkedCount - 1;
            countDisplay.textContent = checkedCount;
            (checkedCount <= 1) ? panel.style.display = 'none' : panel.style.display = 'flex';
        });
    });
    
})();

//open panel for more button(dashboard mobile) 
(function() {
    const btn = document.querySelector('.more');
    const header = document.querySelector('.header-content');
    const nav = document.querySelector('nav');
    const logout = document.querySelector('.log');
    const body = document.body;
    if(!btn || !header) return;

    btn.addEventListener('click', () => {
        body.classList.add('lock-scroll');
        const panel = document.createElement('div');
        panel.className = "more-panel";
        const exit = document.createElement('button');
        exit.className = "exit-panel";
        exit.textContent = 'x';
        nav.style.display = "flex";
        logout.style.display = "flex";
        panel.appendChild(exit);
        panel.appendChild(nav);
        panel.appendChild(logout);
        header.appendChild(panel);
        exit.addEventListener('click', () => {
            console.log("exit");
            body.classList.remove('lock-scroll');
            panel.remove();
        });
    });
})();


//site setting load content logic
function loadContent(pageName) {
    const loader = document.getElementById('loader');
    const displayArea = document.getElementById('display-area');

    //default display
    loader.style.display = 'block';
    displayArea.style.display = 'none';

    fetch('get_page?page=' + pageName)
        .then(response => response.text())
        .then(data => {
            displayArea.innerHTML = data;
            loader.style.display = 'none';
            displayArea.style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            loader.innerHTML = "Failed to load content.";
        });
}


