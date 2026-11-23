document.addEventListener('DOMContentLoaded', function() {
    // ===== 1. KURURA AMAKURU Y'IKIGO (SETTINGS) =====
    fetch('../api/settings.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) return;

            // Shyira amakuru mu ma inputs (Niba ari kuri page)
            if(document.getElementById('school_name')) document.getElementById('school_name').value = data.school_name || '';
            if(document.getElementById('phone')) document.getElementById('phone').value = data.phone || '';
            if(document.getElementById('email')) document.getElementById('email').value = data.email || '';
            if(document.getElementById('address')) document.getElementById('address').value = data.address || '';
            if(document.getElementById('working_hours')) document.getElementById('working_hours').value = data.working_hours || '';
            if(document.getElementById('hero_title')) document.getElementById('hero_title').value = data.hero_title || '';
            if(document.getElementById('hero_subtitle')) document.getElementById('hero_subtitle').value = data.hero_subtitle || '';
            if(document.getElementById('hero_badge')) document.getElementById('hero_badge').value = data.hero_badge || '';
            if(document.getElementById('about_text')) document.getElementById('about_text').value = data.about_text || '';
        })
        .catch(err => console.error('Error fetching settings:', err));

    // ===== 2. BIKA AMAKURU (SETTINGS) =====
    const settingsForm = document.getElementById('settingsForm');
    if (settingsForm) {
        settingsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Kora FormData itwara amakuru yose ari muri form
            const formData = new FormData(this);
            
            fetch('../api/settings.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Erekana ubutumwa bwa success
                    const alertBox = document.getElementById('alertSuccess');
                    if(alertBox) {
                        alertBox.classList.add('show');
                        setTimeout(() => alertBox.classList.remove('show'), 3000);
                    }
                } else {
                    alert('❌ Habaye ikibazo: ' + (data.error || 'Ntibyakunze'));
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('❌ Ntibishoboye kubika. Reba console (F12).');
            });
        });
    }
});