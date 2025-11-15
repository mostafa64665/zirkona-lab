import nodemailer from "nodemailer";

export default async function handler(req, res) {
  console.log('🔥 Function called!');
  console.log('Method:', req.method);
  console.log('Body:', req.body);
  console.log('ENV check:', {
    hasUser: !!process.env.EMAIL_USER,
    hasPass: !!process.env.EMAIL_PASS,});
  if(req.method !== 'POST') {
    return res.status(405).json({ message: 'Method Not Allowed' });
  }

  const { name, email, product } = req.body;

  console.log('Request body:', req.body);

  let transporter = nodemailer.createTransport({
    service: "gmail",
    auth: {
      user: process.env.EMAIL_USER,
      pass: process.env.EMAIL_PASS,
    },
  });

  try {
    await transporter.sendMail({
      from: process.env.EMAIL_USER,
      to: process.env.EMAIL_USER, // الإيميل اللي هيستلم الطلبات
      subject: `New Order: ${product}`,
      text: `Name: ${name}\nEmail: ${email}\nProduct: ${product}`,
    });

    res.status(200).json({ message: "Email sent!" });
  } catch (error) {
    console.error('Email sending error:', error);
    res.status(500).json({ message: "Failed to send email.", error: error.toString() });
  }
}
