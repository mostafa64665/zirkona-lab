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
    if (existingItem) {
        existingItem.quantity += 1;
        showNotification(`تم زيادة كمية "${name}" في السلة!`, 'success');
    } else {
        cart.push({ name, price, quantity: 1, category });
        showNotification(`تم إضافة "${name}" للسلة!`, 'success');
    }

    localStorage.setItem('zirkonaCart', JSON.stringify(cart));
    saveOrderData();
    updateCartBadge();
}

// Show notification popup
function showNotification(message, type = 'success') {
    // Remove existing notification
    const existing = document.querySelector('.notification-popup');
    if (existing) existing.remove();

    // Create notification
    const notification = document.createElement('div');
    notification.className = `notification-popup fixed top-20 right-4 z-50 p-4 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);

    // Animate out
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Update cart badge in navigation
function updateCartBadge() {
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    let badge = document.querySelector('.cart-badge');
    
    if (!badge) {
        // Create badge if it doesn't exist
        const cartLink = document.querySelector('a[href="cart.html"]');
        if (cartLink) {
            badge = document.createElement('span');
            badge.className = 'cart-badge absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center';
            cartLink.style.position = 'relative';
            cartLink.appendChild(badge);
        }
    }
    
    if (badge) {
        badge.textContent = totalItems;
        badge.style.display = totalItems > 0 ? 'flex' : 'none';
    }
}

document.querySelectorAll('.order-btn').forEach(button => {
    button.addEventListener('click', () => {
        const name = button.dataset.name;
        const price = Number(button.dataset.price);
        const category = button.dataset.category;
        addToCart(name, price, category);
    });
});

// Initialize cart badge on page load
document.addEventListener('DOMContentLoaded', () => {
    updateCartBadge();
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
