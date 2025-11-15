// ناخد بيانات الفورم المخزنة
const formData = JSON.parse(localStorage.getItem('formData'));

if (!formData) {
  // لو مفيش بيانات، نرجع المستخدم أو نطلب منه يعمل appointment
  alert('Please make an appointment first!');
  window.location.href = '/appointment.html';
}

// نحدد كل زرار Order Now
document.querySelectorAll('.product button').forEach(btn => {
  btn.addEventListener('click', () => {
    const productCard = btn.closest('.product'); // نجيب الكارد الأب
    const productName = productCard.querySelector('h3').textContent.trim(); // ناخد اسم المنتج

    const orderData = {
      name: `${formData.firstName} ${formData.lastName}`,
      email: formData.email,
      phone: formData.phone,
      product: productName
    };

    // نبعث البيانات للـ Serverless Function مباشرة على نفس الموقع
    fetch('/api/sendOrder', {  // ده يكون اسم الملف في /api/sendOrder.js
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(orderData)
    })
    .then(res => res.json())
    .then(data => {
      alert(`Order for "${productName}" sent successfully!`);
    })
    .catch(err => {
      console.error(err);
      alert('Failed to send order.');
    });
  });
});
