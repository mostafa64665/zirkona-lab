// ناخد بيانات الفورم المخزنة
const formData = JSON.parse(localStorage.getItem('formData'));

if (!formData) {
  alert('Please make an appointment first!');
  window.location.href = '/appointment.html';
}

// نحدد كل زرار Order Now
document.querySelectorAll('.product button').forEach(btn => {
  btn.addEventListener('click', async (e) => {
    e.preventDefault();
    
    const productCard = btn.closest('.product');
    const productName = productCard.querySelector('h3').textContent.trim();

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

      // 🔥 الجزء المهم: نشيك على الـ response قبل ما نحوله لـ JSON
      console.log('Response status:', response.status);
      console.log('Response ok:', response.ok);

      // نجيب الـ text الأول عشان نشوف إيه الراجع
      const responseText = await response.text();
      console.log('Response text:', responseText);

      let data;
      try {
        // نحاول نحوله لـ JSON
        data = JSON.parse(responseText);
      } catch (parseError) {
        console.error('Response is not JSON:', responseText);
        throw new Error('Server returned invalid response');
      }

      if (response.ok) {
        alert(`✅ Order for "${productName}" sent successfully!`);
      } else {
        throw new Error(data.message || 'Failed to send order');
      }
    } catch (err) {
      console.error('Order error:', err);
      alert(`❌ Failed to send order: ${err.message}`);
    } finally {
      btn.disabled = false;
      btn.textContent = 'Order Now';
    }
  });
});