  if (window.location.pathname.includes("cart.html")) {
    const formData = localStorage.getItem('formData');
    if (!formData) {
      window.location.href = "appointment.html";
    }
  }
  let cart = JSON.parse(localStorage.getItem('zirkonaCart')) || [];

  const btn = document.getElementById('mobile-toggle');
  const menu = document.getElementById('mobile-menu');

  btn.addEventListener('click', () => {
    menu.classList.toggle('hidden');
  });

  document.querySelectorAll('#mobile-menu .mobile-link').forEach(link => {
    link.addEventListener('click', () => menu.classList.add('hidden'));
  });


  // Update cart display
  function updateCart() {
    const cartItems = document.getElementById('cart-items');
    const emptyCart = document.getElementById('empty-cart');


    if (cart.length === 0) {
      emptyCart.classList.remove('hidden');
      cartItems.innerHTML = '';
      updateSummary();
      return;
    }

    emptyCart.classList.add('hidden');

    cartItems.innerHTML = cart.map((item, index) => `
      <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-brand-gold/20 cart-item">
        <div class="flex flex-col md:flex-row justify-between gap-6">
          <div class="flex-1">
            <h3 class="text-xl font-bold text-gray-900 mb-2">${item.name}</h3>
            <p class="text-gray-600 mb-4">${item.category || 'Dental Service'}</p>
            <div class="flex flex-wrap items-center gap-4">
              <div class="flex items-center gap-3 bg-brand-cream/50 rounded-lg px-4 py-2">
                <button onclick="updateQuantity(${index}, -1)" class="text-brand-navy hover:text-brand-green font-bold text-xl">−</button>
                <input type="number" value="${item.quantity}" min="1" onchange="setQuantity(${index}, this.value)" class="w-16 text-center bg-transparent font-semibold text-gray-900 outline-none"/>
                <button onclick="updateQuantity(${index}, 1)" class="text-brand-navy hover:text-brand-green font-bold text-xl">+</button>
              </div>
              <div class="text-2xl font-bold text-brand-navy">
                ${(item.price * item.quantity).toFixed(0)} <span class="text-lg text-gray-500">SAR</span>
              </div>
            </div>
          </div>
          <div class="flex md:flex-col items-center md:items-end justify-between md:justify-start gap-4">
            <div class="text-right">
              <p class="text-sm text-gray-500">Unit Price</p>
              <p class="text-lg font-semibold text-gray-700">${item.price} SAR</p>
            </div>
            <button onclick="removeItem(${index})" class="text-brand-burgundy hover:text-red-700 p-2 rounded-lg hover:bg-red-50">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    `).join('');

    updateSummary();
  }

  // Update order summary
  function updateSummary() {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const total = subtotal;
    document.getElementById('subtotal').textContent = subtotal.toFixed(0) + ' SAR';
    document.getElementById('total').textContent = total.toFixed(0) + ' SAR';
  }

  // Update quantity
  function updateQuantity(index, change) {
    cart[index].quantity = Math.max(1, cart[index].quantity + change);
    localStorage.setItem('zirkonaCart', JSON.stringify(cart));
    updateCart();
  }

  // Set quantity manually
  function setQuantity(index, value) {
    const qty = parseInt(value) || 1;
    cart[index].quantity = Math.max(1, qty);
    localStorage.setItem('zirkonaCart', JSON.stringify(cart));
    updateCart();
  }

  // Remove item
  function removeItem(index) {
    if (confirm('Remove this item from cart?')) {
      cart.splice(index, 1);
      localStorage.setItem('zirkonaCart', JSON.stringify(cart));
      updateCart();
    }
  }

  // Clear cart
  function clearCart() {
    if (confirm('Clear all items from cart?')) {
      cart = [];
      localStorage.setItem('zirkonaCart', JSON.stringify(cart));
      updateCart();
    }
  }

  // Initial cart render
  updateCart();



  // ======================
  // Order Submission Logic
  // ======================
  document.getElementById('checkout-btn').addEventListener('click', async (e) => {
    e.preventDefault();

    const formData = JSON.parse(localStorage.getItem('formData')) || {};

    if (!cart.length) {
      alert('سلة المشتريات فارغة!');
      return;
    }

    const orderData = {
      name: `${formData.firstName || "No"} ${formData.lastName || "Name"}`,
      email: formData.email || "noemail@example.com",
      phone: formData.phone || "N/A",
      products: cart.map(item => ({
        name: item.name,
        quantity: item.quantity,
        price: item.price
      }))
    };

    console.log("Order data being sent:", orderData);

    const btn = e.currentTarget;
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'جاري إرسال الطلب...';

    try {
      // Try different API endpoints (PHP and Node.js)
      const endpoints = [
        '/api/send-order.php',
        '/api/send-order', 
        './api/send-order.php',
        'api/send-order.php'
      ];
      let success = false;
      let lastError = null;

      for (const endpoint of endpoints) {
        try {
          console.log(`Trying endpoint: ${endpoint}`);
          const response = await fetch(endpoint, {
            method: 'POST',
            headers: { 
              'Content-Type': 'application/json',
              'Accept': 'application/json'
            },
            body: JSON.stringify(orderData)
          });
          
          if (response.ok) {
            success = true;
            break;
          }
        } catch (err) {
          console.log(`Endpoint ${endpoint} failed:`, err);
          lastError = err;
          continue;
        }
      }

      if (success) {
        showSuccessModal(`تم إرسال الطلب بنجاح مع ${cart.length} منتجات!`, `سنتواصل معك قريباً على:\n📧 ${orderData.email}\n📞 ${orderData.phone}`);
      } else {
        // Fallback: Show contact info if API fails
        const fallbackMessage = `تم حفظ طلبك محلياً، لكن لم نتمكن من إرساله للخادم.\n\nيرجى التواصل معنا مباشرة لتأكيد الطلب:\n📞 الهاتف: 0551611189 أو 0550911183\n📧 البريد: zirkonalab@gmail.com\n\nتفاصيل طلبك:\n👤 الاسم: ${orderData.name}\n📧 البريد: ${orderData.email}\n📞 الهاتف: ${orderData.phone}\n🛒 المنتجات: ${cart.length} منتج\n💰 المجموع: ${cart.reduce((sum, item) => sum + (item.price * item.quantity), 0)} ريال`;
        
        showErrorModal('تعذر إرسال الطلب', fallbackMessage);
      }

      // Clear cart regardless of API success
      cart = [];
      localStorage.setItem('zirkonaCart', JSON.stringify(cart));
      updateCart();
      
    } catch (err) {
      console.error('Unexpected error:', err);
      showErrorModal('حدث خطأ غير متوقع', 'يرجى المحاولة مرة أخرى أو التواصل معنا مباشرة.');
    } finally {
      btn.disabled = false;
      btn.textContent = originalText;
    }
  });

// Beautiful modal functions
function showSuccessModal(title, message) {
  showModal(title, message, 'success');
}

function showErrorModal(title, message) {
  showModal(title, message, 'error');
}

function showModal(title, message, type = 'success') {
  // Remove existing modal
  const existing = document.querySelector('.notification-modal');
  if (existing) existing.remove();

  // Create modal backdrop
  const backdrop = document.createElement('div');
  backdrop.className = 'notification-modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 opacity-0 transition-opacity duration-300';
  
  // Create modal content
  const modal = document.createElement('div');
  modal.className = 'bg-white rounded-2xl shadow-2xl p-8 mx-4 max-w-lg w-full transform scale-95 transition-transform duration-300';
  
  const icon = type === 'success' 
      ? `<div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
           <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
           </svg>
         </div>`
      : `<div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
           <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
           </svg>
         </div>`;

  modal.innerHTML = `
      ${icon}
      <h3 class="text-xl font-bold text-gray-900 text-center mb-3">
          ${title}
      </h3>
      <div class="text-gray-600 text-center mb-6 leading-relaxed whitespace-pre-line">${message}</div>
      <button class="close-modal w-full bg-gradient-to-r from-blue-500 to-green-500 text-white py-3 px-6 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 transform hover:scale-105">
          حسناً
      </button>
  `;

  backdrop.appendChild(modal);
  document.body.appendChild(backdrop);

  // Animate in
  setTimeout(() => {
      backdrop.style.opacity = '1';
      modal.style.transform = 'scale(1)';
  }, 10);

  // Close modal function
  const closeModal = () => {
      backdrop.style.opacity = '0';
      modal.style.transform = 'scale(0.95)';
      setTimeout(() => backdrop.remove(), 300);
  };

  // Event listeners
  backdrop.addEventListener('click', (e) => {
      if (e.target === backdrop) closeModal();
  });
  
  modal.querySelector('.close-modal').addEventListener('click', closeModal);

  // Auto close after 5 seconds for success messages
  if (type === 'success') {
      setTimeout(closeModal, 5000);
  }
}