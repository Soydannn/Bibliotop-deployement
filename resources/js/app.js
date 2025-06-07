import './bootstrap';
import '../css/app.css'; // ✅ Obligatoire pour que Vite injecte le CSS
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();
