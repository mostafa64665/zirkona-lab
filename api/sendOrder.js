import nodemailer from "nodemailer";

export default async function handler(req, res) {
  const { name, email, product } = req.body;

  // إنشاء transporter باستخدام متغيرات البيئة
  let transporter = nodemailer.createTransport({
    service: "gmail",
    auth: {
      user: process.env.EMAIL_USER,  // من Environment Variable
      pass: process.env.EMAIL_PASS,  // من Environment Variable
    },
  });

  try {
    await transporter.sendMail({
      from: process.env.EMAIL_USER,
      to: process.env.EMAIL_USER, // الإيميل اللي هيوصل عليه الطلب
      subject: `New Order: ${product}`,
      text: `Name: ${name}\nEmail: ${email}\nProduct: ${product}`,
    });

    res.status(200).json({ message: "Email sent!" });
  } catch (error) {
    console.error(error);
    res.status(500).json({ message: "Failed to send email." });
  }
}
