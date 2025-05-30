# 🎨 Proyecto DAW 2024-2025 – Plataforma Web de Joan Mas

## 📝 Descripción del Proyecto
Este proyecto consiste en el desarrollo de una plataforma web para el pintor **Joan Mas**, que permite:

- Explorar su galería de obras de arte.
- Comprar productos relacionados (camisetas, accesorios).
- Solicitar cuadros personalizados con gestión mensual del stock.

Se trata de un sitio dinámico, moderno y responsivo, enfocado tanto en la exposición artística como en la experiencia de usuario.

---

## 🎯 Objetivos Principales

- 🖼️ Exponer digitalmente el trabajo del artista Joan Mas.
- 🛍️ Desarrollar una tienda online funcional.
- 🧾 Incorporar autenticación de usuarios con roles (`admin`, `cliente`).
- 🎨 Habilitar solicitudes de cuadros personalizados con stock limitado mensual.
- 📱 Garantizar diseño responsive para móviles y tablets.
- 🧰 Aplicar tecnologías y buenas prácticas aprendidas durante el ciclo.

---

## 🛠️ Tecnologías Utilizadas

### 🔧 Backend (Laravel)
- Laravel 12 + Sanctum para autenticación basada en tokens.
- Control de acceso por roles (`admin`, `cliente`).
- Rutas API (`api.php`) para productos, galería, login, carrito y perfil.
- Eloquent ORM para manejo de relaciones.

### 🖥️ Frontend (Vue + Quasar)
- Vue 3 con Composition API.
- Quasar Framework para diseño y componentes UI responsivos.
- Pinia como gestor de estado.
- Axios para consumir API REST.
- Rutas protegidas mediante navegación condicional.

### 🗃️ Base de Datos
- MySQL gestionado con phpMyAdmin.

### 🔄 Control de Versiones
- Git y GitHub para gestión del código.
- Rama principal `main` con carpetas separadas para `webArt-back` y `webArt-front`.

---

## 📂 Estructura del Repositorio

```
/Proyecto-DAW-2024-2025
├── webArt-back       # 📦 Proyecto Laravel (API backend)
│   ├── app
│   ├── routes
│   ├── public
│   ├── database
│   └── ...
│
├── webArt-front      # 🌐 Proyecto Vue + Quasar (frontend)
│   ├── src
│   ├── public
│   ├── quasar.config.js
│   └── ...
│
└── README.md         # 📘 Documentación del proyecto
```

---

## 🚧 Estado del Proyecto
- ✅ Backend funcional con autenticación, gestión de productos y carrito.
- ✅ Frontend operativo con login, registro, navegación y visualización de obras.
- 🔄 Pendientes menores: mejoras visuales, validaciones avanzadas, despliegue final.

---

## 👤 Autor
**Gabriel Mas** – Desarrollo Fullstack con Laravel + Vue + Quasar.
