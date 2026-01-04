import fs from 'fs';
import path from 'path';

// Create build directory
if (!fs.existsSync('build')) {
    fs.mkdirSync('build');
}

// Copy static files
const staticFiles = [
    'index.html',
    'about.html', 
    'appointment.html',
    'pricing.html',
    'contact.js',
    'cart.js',
    'cart.html'
];

staticFiles.forEach(file => {
    if (fs.existsSync(file)) {
        fs.copyFileSync(file, path.join('build', file));
    }
});

// Copy directories
const directories = ['assets', 'images', 'js'];

directories.forEach(dir => {
    if (fs.existsSync(dir)) {
        fs.cpSync(dir, path.join('build', dir), { recursive: true });
    }
});

// Copy backend
fs.cpSync('backend', path.join('build', 'api'), { recursive: true });

console.log('Build completed successfully!');