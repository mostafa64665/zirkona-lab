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
      // Try different API endpoints
      const endpoints = ['/api/send-contact', './api/send-contact', 'api/send-contact'];
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
      
      alert('✅ تم إرسال الرسالة بنجاح! سنتواصل معك قريباً');
      form.reset();
      
    } catch (err) {
      console.error('Send error:', err);
      
      // Fallback: Show contact info if API fails
      const fallbackMessage = `
❌ عذراً، حدث خطأ في إرسال الرسالة.

يمكنك التواصل معنا مباشرة:
📞 الهاتف: 0551611189 أو 0550911183
📧 البريد: zirkonalab@gmail.com

أو حاول مرة أخرى لاحقاً.
      `;
      
      alert(fallbackMessage);
    } finally {
      btn.disabled = false;
      btn.textContent = originalText;
    }
  });
}
