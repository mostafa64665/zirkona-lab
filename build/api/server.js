import express from "express";
import nodemailer from "nodemailer";
import dotenv from "dotenv";
import cors from "cors";
import path from "path";
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

dotenv.config();

const app = express();
app.use(cors());
app.use(express.json());

// Serve static files
app.use(express.static(path.join(__dirname, '../')));

// Health check endpoint
app.get('/api/health', (req, res) => {
  res.json({ 
    status: 'OK', 
    timestamp: new Date().toISOString(),
    env: {
      EMAIL_USER: process.env.EMAIL_USER ? 'Set' : 'Not Set',
      EMAIL_PASS: process.env.EMAIL_PASS ? 'Set' : 'Not Set',
      PORT: process.env.PORT || 'Default'
    }
  });
});

// -----------------------------
// Send Order
// -----------------------------
app.post("/api/send-order", async (req, res) => {
  console.log("Received order body:", req.body);

  let { name, email, phone, products } = req.body;

  name = name || "No Name";
  email = email || "noemail@example.com";
  phone = phone || "N/A";
  products = Array.isArray(products)
    ? products.filter(p => p.name && p.quantity && p.price)
    : [];

  if (!products.length) {
    console.warn("No valid products received");
    return res.status(200).json({ message: "Order received but no valid products", skipped: true });
  }

  try {
    const transporter = nodemailer.createTransport({
      service: "gmail",
      auth: {
        user: process.env.EMAIL_USER,
        pass: process.env.EMAIL_PASS,
      },
    });

    const totalAmount = products.reduce((sum,p) => sum + p.price*p.quantity, 0);

    const htmlContent = `
      <div style="font-family: 'Segoe UI', sans-serif; max-width:600px; margin:auto; padding:20px; border-radius:10px; background-color:#f5f5f5; color:#333;">
        <h2 style="color:#1d3557; text-align:center;">🛒 New Order Received</h2>
        <div style="margin-top:20px; padding:15px; background-color:#fff; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.1);">
          <p><strong>Name:</strong> ${name}</p>
          <p><strong>Email:</strong> ${email}</p>
          <p><strong>Phone:</strong> ${phone}</p>
          <h3 style="margin-top:20px; color:#1d3557;">Products:</h3>
          <table style="width:100%; border-collapse: collapse; margin-top:10px;">
            <thead>
              <tr style="background-color:#a8dadc; color:#1d3557;">
                <th style="border:1px solid #ddd; padding:8px; text-align:left;">Product</th>
                <th style="border:1px solid #ddd; padding:8px; text-align:right;">Qty</th>
                <th style="border:1px solid #ddd; padding:8px; text-align:right;">Price (SAR)</th>
              </tr>
            </thead>
            <tbody>
              ${products.map(p => `
                <tr>
                  <td style="border:1px solid #ddd; padding:8px;">${p.name}</td>
                  <td style="border:1px solid #ddd; padding:8px; text-align:right;">${p.quantity}</td>
                  <td style="border:1px solid #ddd; padding:8px; text-align:right;">${p.price * p.quantity}</td>
                </tr>
              `).join('')}
              <tr style="background-color:#e63946; color:#fff; font-weight:bold;">
                <td colspan="2" style="border:1px solid #ddd; padding:8px; text-align:right;">Total</td>
                <td style="border:1px solid #ddd; padding:8px; text-align:right;">${totalAmount}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <p style="margin-top:30px; text-align:center; color:#555; font-size:14px;">Thank you for your order! 🌟</p>
      </div>
    `;

    await transporter.sendMail({
      from: process.env.EMAIL_USER,
      to: process.env.EMAIL_USER,
      subject: `🎉 New Order (${products.length} products)`,
      html: htmlContent
    });

    console.log("Order email sent successfully");
    res.status(200).json({ message: "Order sent successfully" });

  } catch (err) {
    console.error("Order Email Error:", err.message);
    res.status(500).json({ message: "Order received but email failed", error: err.message });
  }
});

// -----------------------------
// Send Contact Form
// -----------------------------
app.post("/api/send-contact", async (req, res) => {
  console.log("Received contact body:", req.body);

  const { name, email, phone, message } = req.body;

  if (!name || !email || !message) {
    return res.status(400).json({ message: "Missing required fields" });
  }

  try {
    const transporter = nodemailer.createTransport({
      service: "gmail",
      auth: {
        user: process.env.EMAIL_USER,
        pass: process.env.EMAIL_PASS,
      },
    });

    const htmlContent = `
      <div style="font-family: 'Segoe UI', sans-serif; max-width:600px; margin:auto; padding:20px; border-radius:10px; background-color:#f5f5f5; color:#333;">
        <h2 style="color:#1d3557; text-align:center;">📩 New Contact Message</h2>
        <div style="margin-top:20px; padding:15px; background-color:#fff; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.1);">
          <p><strong>Name:</strong> ${name}</p>
          <p><strong>Email:</strong> ${email}</p>
          <p><strong>Phone:</strong> ${phone || 'N/A'}</p>
          <p><strong>Message:</strong><br>${message}</p>
        </div>
        <p style="margin-top:30px; text-align:center; color:#555; font-size:14px;">Thank you for contacting us! 🌟</p>
      </div>
    `;

    await transporter.sendMail({
      from: process.env.EMAIL_USER,
      to: process.env.EMAIL_USER,
      subject: `📩 New Contact Message from ${name}`,
      html: htmlContent
    });

    console.log("Contact email sent successfully");
    res.status(200).json({ message: "Contact message sent successfully" });

  } catch (err) {
    console.error("Contact Email Error:", err.message);
    res.status(500).json({ message: "Failed to send contact email", error: err.message });
  }
});

const PORT = process.env.PORT || 3001;
app.listen(PORT, () => console.log(`🚀 Server running on port ${PORT}`));
