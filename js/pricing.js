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
        showToast(`تم زيادة كمية "${name}" في السلة!`, 'success');
    } else {
        cart.push({ name, price, quantity: 1, category });
        showToast(`تم إضافة "${name}" للسلة!`, 'success');
    }

    localStorage.setItem('zirkonaCart', JSON.stringify(cart));
    saveOrderData();
    updateCartBadge();
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
    // Add hover effects
    button.addEventListener('mouseenter', () => {
        button.style.transform = 'translateY(-2px)';
        button.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.15)';
        button.style.transition = 'all 0.3s ease';
    });
    
    button.addEventListener('mouseleave', () => {
        button.style.transform = 'translateY(0)';
        button.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.1)';
    });
    
    // Add click animation
    button.addEventListener('mousedown', () => {
        button.style.transform = 'translateY(0) scale(0.98)';
    });
    
    button.addEventListener('mouseup', () => {
        button.style.transform = 'translateY(-2px) scale(1)';
    });

    button.addEventListener('click', (e) => {
        // Add ripple effect
        const ripple = document.createElement('span');
        const rect = button.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.cssText = `
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s ease-out;
            pointer-events: none;
        `;
        
        // Add ripple animation CSS if not exists
        if (!document.querySelector('#ripple-style')) {
            const style = document.createElement('style');
            style.id = 'ripple-style';
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(2);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }
        
        button.style.position = 'relative';
        button.style.overflow = 'hidden';
        button.appendChild(ripple);
        
        setTimeout(() => ripple.remove(), 600);
        
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
