import nodemailer from "nodemailer";

export default async function handler(req, res) {
  // Log عشان نشوف لو الـ function اتنادت أصلاً
  console.log('🔥 Function called!', req.method);

  if (req.method !== 'POST') {
    return res.status(405).json({ message: 'Method Not Allowed' });
  }

  try {
    // Check ENV
    if (!process.env.EMAIL_USER || !process.env.EMAIL_PASS) {
      console.error('❌ ENV variables missing!');
      return res.status(500).json({ 
        message: 'Server configuration error' 
      });
    }

    const { name, email, phone, product } = req.body;
    console.log('📦 Order data:', { name, email, phone, product });

    let transporter = nodemailer.createTransport({
      service: "gmail",
      auth: {
        user: process.env.EMAIL_USER,
        pass: process.env.EMAIL_PASS,
      },
    });

    console.log('📧 Sending email...');
    
    await transporter.sendMail({
      from: process.env.EMAIL_USER,
      to: process.env.EMAIL_USER,
      subject: `New Order: ${product}`,
      text: `Name: ${name}\nEmail: ${email}\nPhone: ${phone}\nProduct: ${product}`,
    });

    console.log('✅ Email sent!');
    return res.status(200).json({ message: "Email sent successfully!" });

  } catch (error) {
    console.error('❌ Error:', error.message);
    return res.status(500).json({ 
      message: "Failed to send email", 
      error: error.message 
    });
  }
}