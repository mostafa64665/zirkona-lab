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
      
      showToast('تم إرسال الرسالة بنجاح!', 'success');
      form.reset();
      
    } catch (err) {
      console.error('Send error:', err);
      
      // Fallback: Show contact info if API fails
      showToast('عذراً، حدث خطأ في إرسال الرسالة', 'error');
    } finally {
      btn.disabled = false;
      btn.textContent = originalText;
    }
  });
}
