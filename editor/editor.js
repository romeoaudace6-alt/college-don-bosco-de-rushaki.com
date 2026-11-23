document.addEventListener('DOMContentLoaded', function() {
    function loadStats() {
        fetch('../api/data.php?action=stats')
            .then(r => r.json())
            .then(data => {
                if(document.getElementById('statStaff')) document.getElementById('statStaff').innerText = data.staff || 0;
                if(document.getElementById('statEvents')) document.getElementById('statEvents').innerText = data.events || 0;
                if(document.getElementById('statNews')) document.getElementById('statNews').innerText = data.news || 0;
                if(document.getElementById('statGallery')) document.getElementById('statGallery').innerText = data.gallery || 0;
            });
    }

    function loadSettings() {
        fetch('../api/settings.php')
            .then(r => r.json())
            .then(data => {
                if (data.error) return;
                ['hero_title','hero_subtitle','hero_badge','about_text'].forEach(id => {
                    let el = document.getElementById(id);
                    if(el) el.value = data[id] || '';
                });
            });
    }

    function loadStaff() {
        fetch('../api/data.php?action=get_staff')
            .then(r => r.json())
            .then(data => {
                let html = '<table><thead><tr><th>Amazina</th><th>Umwanya</th><th>Icyiciro</th><th>Actions</th></tr></thead><tbody>';
                data.forEach(s => {
                    html += `<tr><td>${s.full_name}</td><td>${s.position}</td><td>${s.category}</td><td><button onclick="deleteItem('staff', ${s.id})" class="btn-delete">Siba</button></td></tr>`;
                });
                html += '</tbody></table>';
                if(document.getElementById('staffList')) document.getElementById('staffList').innerHTML = html;
            });
    }

    function loadEvents() {
        fetch('../api/data.php?action=get_events')
            .then(r => r.json())
            .then(data => {
                let html = '<table><thead><tr><th>Izina</th><th>Itariki</th><th>Actions</th></tr></thead><tbody>';
                data.forEach(e => {
                    html += `<tr><td>${e.title}</td><td>${e.event_date}</td><td><button onclick="deleteItem('event', ${e.id})" class="btn-delete">Siba</button></td></tr>`;
                });
                html += '</tbody></table>';
                if(document.getElementById('eventsList')) document.getElementById('eventsList').innerHTML = html;
            });
    }

    function loadNews() {
        fetch('../api/data.php?action=get_news')
            .then(r => r.json())
            .then(data => {
                let html = '<table><thead><tr><th>Umutwe</th><th>Itariki</th><th>Actions</th></tr></thead><tbody>';
                data.forEach(n => {
                    html += `<tr><td>${n.title}</td><td>${n.date_posted}</td><td><button onclick="deleteItem('news', ${n.id})" class="btn-delete">Siba</button></td></tr>`;
                });
                html += '</tbody></table>';
                if(document.getElementById('newsList')) document.getElementById('newsList').innerHTML = html;
            });
    }

    function loadGallery() {
        fetch('../api/data.php?action=get_gallery')
            .then(r => r.json())
            .then(data => {
                let html = '<table><thead><tr><th>Ibisobanuro</th><th>Itariki</th><th>Actions</th></tr></thead><tbody>';
                data.forEach(g => {
                    html += `<tr><td>${g.caption}</td><td>${g.date_posted}</td><td><button onclick="deleteItem('gallery', ${g.id})" class="btn-delete">Siba</button></td></tr>`;
                });
                html += '</tbody></table>';
                if(document.getElementById('galleryList')) document.getElementById('galleryList').innerHTML = html;
            });
    }

    window.deleteItem = function(type, id) {
        if(!confirm('Urabyizeye ko ushaka gusiba?')) return;
        let formData = new FormData();
        formData.append('id', id);
        fetch(`../api/data.php?action=delete_${type}`, { method: 'POST', body: formData })
            .then(r => r.json())
            .then(() => {
                if(type === 'staff') loadStaff();
                if(type === 'event') loadEvents();
                if(type === 'news') loadNews();
                if(type === 'gallery') loadGallery();
            });
    };

    document.getElementById('mainSettingsForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        fetch('../api/settings.php', { method: 'POST', body: new FormData(this) })
            .then(r => r.json()).then(data => { if(data.success) alert('Byabitswe!'); });
    });

    document.getElementById('addStaffForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        fetch('../api/data.php?action=add_staff', { method: 'POST', body: new FormData(this) })
            .then(r => r.json()).then(() => { loadStaff(); loadStats(); this.reset(); });
    });

    document.getElementById('addEventForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        fetch('../api/data.php?action=add_event', { method: 'POST', body: new FormData(this) })
            .then(r => r.json()).then(() => { loadEvents(); loadStats(); this.reset(); });
    });

    document.getElementById('addNewsForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        fetch('../api/data.php?action=add_news', { method: 'POST', body: new FormData(this) })
            .then(r => r.json()).then(() => { loadNews(); loadStats(); this.reset(); });
    });

    document.getElementById('addGalleryForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        fetch('../api/data.php?action=add_gallery', { method: 'POST', body: new FormData(this) })
            .then(r => r.json()).then(() => { loadGallery(); loadStats(); this.reset(); });
    });

    loadSettings();
    loadStats();
    loadStaff();
    loadEvents();
    loadNews();
    loadGallery();
});