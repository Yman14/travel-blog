(function() {
    const header = document.querySelector('.site-header');
    const nav = document.querySelector('.site-nav');
    const media = document.querySelector('.social-media');
    const menu = document.querySelector('.menu-btn');

    if(!menu || !header) return;

    menu.addEventListener('click', () => {
        const body = document.body;
        body.classList.add('locked-scroll');

        const panel = document.createElement('div');
        panel.className = 'menu-panel';
        const exit = document.createElement('button');
        exit.className = 'panel-exit';
        exit.textContent = 'X';
        nav.style.display = 'flex';
        media.style.display = 'flex';
        panel.append(exit, nav, media);
        header.appendChild(panel);

        exit.addEventListener('click', () => {
            console.log("exit");
            body.classList.remove('locked-scroll');
            panel.remove();
        });
    });

})();