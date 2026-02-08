let cart = [];
let paymentMethodId = null;

function format(n) {
    return new Intl.NumberFormat('id-ID').format(n);
}

function renderCart() {
    const el = document.getElementById('cart-items');
    el.innerHTML = '';

    let total = 0;

    cart.forEach((item, i) => {
        total += item.qty * item.price;

        el.innerHTML += `
            <div class="cart-item">
                <div>
                    <strong>${item.name}</strong>
                    <div class="qty">
                        <button onclick="changeQty(${i},-1)">-</button>
                        <span>${item.qty}</span>
                        <button onclick="changeQty(${i},1)">+</button>
                    </div>
                </div>
                <div>Rp ${format(item.qty * item.price)}</div>
            </div>
        `;
    });

    document.getElementById('grand-total').innerText =
        'Rp ' + format(total);
}

function changeQty(index, delta) {
    cart[index].qty += delta;
    if (cart[index].qty <= 0) cart.splice(index,1);
    renderCart();
}

document.querySelectorAll('.product-card').forEach(card => {
    card.addEventListener('click', () => {
        const id = card.dataset.id;
        const found = cart.find(i => i.id == id);

        if (found) {
            found.qty++;
        } else {
            cart.push({
                id: id,
                name: card.dataset.name,
                price: parseFloat(card.dataset.price),
                qty: 1
            });
        }
        renderCart();
    });
});

document.querySelectorAll('.category-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const cid = btn.dataset.categoryId;
        document.querySelectorAll('.product-card').forEach(p => {
            p.style.display = p.dataset.category == cid ? 'block' : 'none';
        });
    });
});

document.querySelectorAll('.payment-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.payment-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        paymentMethodId = btn.dataset.id;
    });
});

document.getElementById('btn-submit').addEventListener('click', () => {

    if (!cart.length) {
        alert('Item kosong');
        return;
    }

    if (!paymentMethodId) {
        alert('Pilih metode pembayaran');
        return;
    }

    if (!confirm('Simpan transaksi?')) return;

    fetch(window.SALES_CONFIG.submitUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.SALES_CONFIG.csrf
        },
        body: JSON.stringify({
            date: window.SALES_CONFIG.today,
            payment_type: 'CASH',
            payment_method_id: paymentMethodId,
            subtotal: cart.reduce((t,i)=>t+i.qty*i.price,0),
            grand_total: cart.reduce((t,i)=>t+i.qty*i.price,0),
            items: cart.map(i => ({
                product_id: i.id,
                qty: i.qty,
                price: i.price,
                subtotal: i.qty * i.price
            }))
        })
    }).then(() => location.href = '/sales');
});
