if ($('.toggle').length > 0) {
  $('.toggle').click(function () {
    $('.text-1').not($(this).next()).slideUp(500);
    $(this).next().slideToggle(500);
  });
}
const formData = JSON.parse(localStorage.getItem('formData'));
if (!formData) {
  alert('Please make an appointment first!');
  window.location.href = '/appointment.html';
}

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

      const responseText = await response.text();

      let data;
      try {
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