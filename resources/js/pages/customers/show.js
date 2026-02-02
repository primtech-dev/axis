import $ from 'jquery';

$(function() {
    const btn = document.getElementById('btnToggleActive');
    if (!btn) return;

    btn.addEventListener('click', function (e) {
        e.preventDefault();

        if (!window.customerToggleRoute) {
            console.error('customerToggleRoute not set.');
            return;
        }

        if (!confirm('Yakin ingin mengubah status aktif pelanggan ini?')) return;

        fetch(window.customerToggleRoute, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
            .then(async res => {
                const json = await res.json().catch(()=>({}));
                if (!res.ok) throw json;
                return json;
            })
            .then(data => {
                // update UI
                const statusText = document.getElementById('customerStatusText');
                if (statusText) statusText.textContent = data.is_active ? 'Aktif' : 'Non-aktif';

                // toggle button label & class
                if (data.is_active) {
                    btn.textContent = 'Non-aktifkan';
                    btn.classList.remove('btn-outline-success');
                    btn.classList.add('btn-outline-danger');
                } else {
                    btn.textContent = 'Aktifkan';
                    btn.classList.remove('btn-outline-danger');
                    btn.classList.add('btn-outline-success');
                }

                if (window.toast) window.toast.success(data.message || 'Status diperbarui');
            })
            .catch(err => {
                console.error('Toggle active error', err);
                const msg = (err && err.message) ? err.message : 'Gagal mengubah status pelanggan';
                if (window.toast) window.toast.error(msg);
                alert(msg);
            });
    });
});
