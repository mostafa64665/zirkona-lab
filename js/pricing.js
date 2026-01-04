if (window.location.pathname.includes("pricing.html")) {
    const formData = localStorage.getItem('formData');
    if (!formData) {
        window.location.href = "appointment.html";
    }
}


const btn = document.getElementById('mobile-toggle');
const menu = document.getElementById('mobile-menu');

btn.addEventListener('click', () => {
    menu.classList.toggle('hidden');
});

document.querySelectorAll('#mobile-menu .mobile-link').forEach(link => {
    link.addEventListener('click', () => menu.classList.add('hidden'));
});


if ($('.toggle').length > 0) {
    $('.toggle').click(function () {
        $('.text-1').not($(this).next()).slideUp(500);
        $(this).next().slideToggle(500);
    });
}

let cart = JSON.parse(localStorage.getItem('zirkonaCart')) || [];

function saveOrderData() {
    const formData = JSON.parse(localStorage.getItem('formData'));
    if (!formData) return;

    const orderData = {
        name: `${formData.firstName} ${formData.lastName}`,
        email: formData.email,
        phone: formData.phone,
        products: cart.map(item => ({
            name: item.name,
            quantity: item.quantity,
            price: item.price
        }))
    };

    localStorage.setItem('orderData', JSON.stringify(orderData));
}

function addToCart(name, price, category) {
    const existingItem = cart.find(item => item.name === name);
    if (existingItem) existingItem.quantity += 1;
    else cart.push({ name, price, quantity: 1, category });

    localStorage.setItem('zirkonaCart', JSON.stringify(cart));
    saveOrderData();
    alert('تم إضافة المنتج للسلة!');
}

document.querySelectorAll('.order-btn').forEach(button => {
    button.addEventListener('click', () => {
        const name = button.dataset.name;
        const price = Number(button.dataset.price);
        const category = button.dataset.category;
        addToCart(name, price, category);
    });
});


const checkoutBtn = document.getElementById('checkout-btn');
if (checkoutBtn) {
    checkoutBtn.addEventListener('click', async (e) => {
        e.preventDefault();

        const orderData = JSON.parse(localStorage.getItem('orderData')) || {};
        if (!orderData.products || !orderData.products.length) {
            alert('سلة المشتريات فارغة!');
            return;
        }

        const btn = e.currentTarget;
        btn.disabled = true;
        btn.textContent = 'جاري الإرسال...';

        try {
            const response = await fetch('/api/send-order', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(orderData)
            });

            const data = await response.json().catch(() => null);

            alert(`✅ تم إرسال الطلب بنجاح مع ${orderData.products.length} منتجات!`);

            localStorage.removeItem('zirkonaCart');
            localStorage.removeItem('orderData');
            cart = [];
            updateCart();
            if (data && data.error) console.error('Server issue:', data.error);
        } catch (err) {
            console.error('Fetch error:', err);
            alert('❌ حدث خطأ أثناء إرسال الطلب.');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Proceed to Checkout';
        }
    });
}
