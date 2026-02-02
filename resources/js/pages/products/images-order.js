// resources/js/pages/products/images-order.js
import Sortable from 'sortablejs';
import $ from 'jquery';

const csrfMeta = document.querySelector('meta[name="csrf-token"]');
const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;

if (csrfToken) {
    // global setup untuk jQuery AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });
} else {
    console.warn('CSRF token meta tag not found. Ajax requests may fail.');
}

function toastSuccess(msg){ if (window.toast && typeof window.toast.success === 'function') window.toast.success(msg); else console.log('OK:', msg); }
function toastError(msg){ if (window.toast && typeof window.toast.error === 'function') window.toast.error(msg); else console.error('ERR:', msg); }

$(function(){
    const gallery = document.getElementById('productGallery');
    if (!gallery) {
        console.debug('images-order: gallery not found, skipping init.');
        return;
    }

    // get product id safely
    const productId = gallery.getAttribute('data-product-id') || (document.querySelector('meta[name="product-id"]') ? document.querySelector('meta[name="product-id"]').getAttribute('content') : null);
    if (!productId) console.warn('images-order: product id not found on gallery element.');

    // Init Sortable
    let sortable;
    try {
        sortable = Sortable.create(gallery, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            // handle: '.drag-handle', // uncomment if you add a drag handle element inside each item
            onEnd: function(evt){
                try {
                    // collect only existing gallery-item elements and read their data-id
                    const nodes = Array.from(gallery.querySelectorAll('.gallery-item'));
                    const ids = nodes.map(el => el ? el.getAttribute('data-id') : null).filter(Boolean);

                    if (!ids.length) {
                        console.debug('images-order: no image ids resolved, skip reorder ajax.');
                        return;
                    }

                    // send ajax
                    $.ajax({
                        url: `/products/${productId}/images/reorder`,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') || {}).content || ''},
                        data: { order: ids },
                        success(resp){
                            if (resp && resp.success) {
                                // update UI positions shown
                                gallery.querySelectorAll('.gallery-item').forEach((el, idx) => {
                                    const posEl = el.querySelector('.img-pos');
                                    if (posEl) posEl.textContent = idx;
                                });
                                toastSuccess('Urutan gambar tersimpan');
                            } else {
                                toastError((resp && resp.message) ? resp.message : 'Gagal menyimpan urutan');
                            }
                        },
                        error(xhr){
                            let msg = 'Gagal menyimpan urutan (server)';
                            if (xhr && xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                            toastError(msg);
                            console.error('images-order reorder error', xhr);
                        }
                    });
                } catch (err) {
                    console.error('images-order onEnd error', err);
                }
            }
        });
    } catch (err) {
        console.error('images-order init failed', err);
    }

    // set main image
    $(document).on('click', '.js-set-main', function(){
        const id = $(this).data('id');
        if (!id) { toastError('ID gambar tidak ditemukan'); return; }
        if (!confirm('Tandai gambar ini sebagai gambar utama?')) return;

        $.ajax({
            url: `/products/images/${id}/set-main`,
            method: 'POST',
            headers: {'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') || {}).content || ''},
            success(resp){
                if (resp && resp.success) {
                    toastSuccess('Gambar ditandai utama');
                    // update UI: reload page to keep things simple and consistent
                    location.reload();
                } else {
                    toastError(resp.message || 'Gagal set utama');
                }
            },
            error(xhr){
                let msg = 'Gagal set utama (server)';
                if (xhr && xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                toastError(msg);
                console.error('images-order set-main error', xhr);
            }
        });
    });

    // optional: if you want to add Save Order button instead of auto-save, you can implement here

});
