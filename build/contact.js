const contactForm = document.getElementById('contact-form');

if (contactForm) {
  contactForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const form = e.currentTarget;
    const formData = {
      name: form.name.value.trim(),
      email: form.email.value.trim(),
      phone: form.phone.value.trim(),
      message: form.message.value.trim()
    };

    // Validate required fields
    if (!formData.name || !formData.email || !formData.message) {
      alert('❌ يرجى ملء جميع الحقول المطلوبة');
      return;
    }

    // Validate email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(formData.email)) {
      alert('❌ يرجى إدخال بريد إلكتروني صحيح');
      return;
    }

    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'جاري الإرسال...';

    try {
      // Try different API endpoints (PHP and Node.js)
      const endpoints = [
        '/api/send-contact.php',
        '/api/send-contact', 
        './api/send-contact.php', 
        'api/send-contact.php'
      ];
      let response = null;
      let lastError = null;

      for (const endpoint of endpoints) {
        try {
          console.log(`Trying endpoint: ${endpoint}`);
          response = await fetch(endpoint, {
            method: 'POST',
            headers: { 
              'Content-Type': 'application/json',
              'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
          });
          
          if (response.ok) {
            break; // Success, exit loop
          }
        } catch (err) {
          console.log(`Endpoint ${endpoint} failed:`, err);
          lastError = err;
          continue; // Try next endpoint
        }
      }

      if (!response || !response.ok) {
        throw new Error(lastError?.message || 'جميع نقاط الاتصال فشلت');
      }

      const data = await response.json().catch(() => ({}));
      
      showSuccessModal('تم إرسال الرسالة بنجاح!', 'سنتواصل معك قريباً');
      form.reset();
      
    } catch (err) {
      console.error('Send error:', err);
      
      // Fallback: Show contact info if API fails
      const fallbackMessage = `يمكنك التواصل معنا مباشرة:\n📞 الهاتف: 0551611189 أو 0550911183\n📧 البريد: zirkonalab@gmail.com\n\nأو حاول مرة أخرى لاحقاً.`;
      
      showErrorModal('عذراً، حدث خطأ في إرسال الرسالة', fallbackMessage);
    } finally {
      btn.disabled = false;
      btn.textContent = originalText;
    }
  });
}

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

  // Auto close after 4 seconds for success messages
  if (type === 'success') {
      setTimeout(closeModal, 4000);
  }
}
