// ناخد بيانات الفورم المخزنة
const formData = JSON.parse(localStorage.getItem('formData'));

if (!formData) {
  alert('Please make an appointment first!');
  window.location.href = '/appointment.html';
}

// نحدد كل زرار Order Now
document.querySelectorAll('.product button').forEach(btn => {
  btn.addEventListener('click', async (e) => {
    e.preventDefault(); // منع أي سلوك افتراضي
    
    const productCard = btn.closest('.product');
    const productName = productCard.querySelector('h3').textContent.trim();

    // نعطل الزرار مؤقتاً
    btn.disabled = true;
    btn.textContent = 'Sending...';

    const orderData = {
      name: `${formData.firstName} ${formData.lastName}`,
      email: formData.email,
      phone: formData.phone,
      product: productName
    };

    try {
      const response = await fetch('/api/sendOrder', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(orderData)
      });

      const data = await response.json();

      if (response.ok) {
        alert(`✅ Order for "${productName}" sent successfully!`);
      } else {
        throw new Error(data.message || 'Failed to send order');
      }
    } catch (err) {
      console.error('Order error:', err);
      alert('❌ Failed to send order. Please try again.');
    } finally {
      // نرجّع الزرار لحالته الطبيعية
      btn.disabled = false;
      btn.textContent = 'Order Now';
    }
  });
});