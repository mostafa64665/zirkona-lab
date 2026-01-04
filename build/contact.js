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

    const btn = form.querySelector('button');
    btn.disabled = true;
    btn.textContent = 'Sending...';

    try {
      const response = await fetch('/api/send-contact', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
      });

      const data = await response.json().catch(() => null);

      if (response.ok) {
        alert('✅ Message sent successfully!');
        form.reset(); // يمسح الفورم بعد الإرسال
      } else {
        throw new Error(data?.message || 'Failed to send message');
      }
    } catch (err) {
      console.error('Send error:', err);
      alert('❌ Failed to send message.');
    } finally {
      btn.disabled = false;
      btn.textContent = 'Send Message';
    }
  });
}
