
# ⚡ Kali-Chat Messenger by SHAID SK
### The Next-Gen Real-Time Communication Platform

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Vue.js](https://img.shields.io/badge/Vue.js-3-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Reverb](https://img.shields.io/badge/Reverb-WebSockets-orange?style=for-the-badge)
![License](https://img.shields.io/badge/License-MIT-blue?style=for-the-badge)

<div align="center">
  <p>
    <strong>Zero Latency. Infinite Style. Total Security.</strong>
  </p>
  <p>
    A high-performance real-time chat application featuring a <strong>Cyber-SaaS aesthetic</strong>, 
    <strong>Glassmorphism UI</strong>, and <strong>3D Interactive Elements</strong>.
  </p>
  <a href="#-installation"><strong>Explore the Docs »</strong></a>
  <br />
  <br />
  <a href="#-screenshots">View Demo</a>
  ·
  <a href="#-features">Report Bug</a>
  ·
  <a href="#-roadmap">Request Feature</a>
</div>

---

## 🔮 Overview

**Kali-chat Messenger** is not just a chat app; it's a visual experience. Built on the bleeding edge of the PHP ecosystem using **Laravel 11** and **Laravel Reverb**, it delivers WebSocket-powered communication without the need for external services like Pusher.

The frontend is crafted with **Vue 3 (Composition API)** and **Inertia.js**, wrapped in a stunning **Cyberpunk/Deep Space** UI that features real-time physics-based animations, 3D tilt effects, and holographic interfaces.

---

## 🚀 Features

### **✨ Core Experience**
* **Real-Time Messaging:** Instant delivery via Secure WebSockets (Laravel Reverb).
* **Live Presence:** See who is online/offline instantly with glowing status indicators.
* **Typing Indicators:** Real-time feedback when other users are composing messages.
* **Read Receipts:** WhatsApp-style Double Tick system (Grey for sent, Green for read).
* **Image Sharing:** Drag-and-drop image uploads with instant preview and holographic hover effects.

### **🎨 Cyber-SaaS UI/UX**
* **Glassmorphism:** Frosted glass panels (`backdrop-blur-xl`) throughout the dashboard and chat.
* **3D Parallax Tilt:** Login and Register cards react to mouse movement in 3D space.
* **Living Backgrounds:** Animated organic orbs and scanning laser grids.
* **Micro-Interactions:** Spring-physics message bubbles and punchy button clicks.
* **Danger Zone:** specialized UI for account deletion with red warning tape aesthetics.

---

## 🛠️ Tech Stack

<details>
<summary><strong>Click to expand Tech Stack details</strong></summary>

| Component | Technology | Description |
| :--- | :--- | :--- |
| **Backend** | Laravel 11 | The PHP Framework for Web Artisans. |
| **Real-Time** | Laravel Reverb | First-party WebSocket server for Laravel. |
| **Database** | SQLite | Serverless, zero-configuration database engine. |
| **Frontend** | Vue 3 | The Progressive JavaScript Framework (Composition API). |
| **Bridge** | Inertia.js | Modern monolith architecture (No API required). |
| **Styling** | Tailwind CSS | Utility-first CSS framework. |
| **Icons** | Heroicons | Beautiful hand-crafted SVGs. |

</details>

---

## ⚡ Installation

Follow these steps to deploy the Reverb grid on your local machine.

### **Prerequisites**
* PHP 8.2+
* Node.js & NPM
* Composer

### **1. Clone the Repository**
```bash
git clone https://github.com/ShAiDSk/kali_chat-messenger.git
cd kali_chat-messenger

### **2. Install Dependencies**

```bash
# Backend
composer install

# Frontend
npm install
```

### **3. Environment Setup**

```bash
cp .env.example .env
php artisan key:generate
```

**Configure SQLite (Recommended for Dev):**
Open `.env` and set:

```env
DB_CONNECTION=sqlite
# Remove other DB_ lines
```

*Create the database file:*

```bash
touch database/database.sqlite
```

**Configure Reverb (WebSockets):**
Ensure these are in your `.env`:

```env
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=my-app-id
REVERB_APP_KEY=my-app-key
REVERB_APP_SECRET=my-app-secret
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

### **4. Database Migration**

```bash
php artisan migrate
```

-----

## 🖥️ Running the App

You need **3 terminals** running simultaneously to power the Reverb Grid.

### **Terminal 1: Laravel Server**

```bash
php artisan serve
```

### **Terminal 2: Frontend Bundler (Vite)**

```bash
npm run dev
```

### **Terminal 3: WebSocket Server (Reverb)**

```bash
php artisan reverb:start
```

🚀 **Access the app:** `http://127.0.0.1:8000`

-----

## 📸 Screenshots

| Login Portal (3D Tilt) | Dashboard (Cyber Grid) |
| :---: | :---: |
|  |  |

| Chat Interface (Glass) | Profile (Danger Zone) |
| :---: | :---: |
|  |  |

-----

## 🧩 Directory Structure

```
reverb-messenger/
├── app/
│   ├── Events/            # Real-time Events (MessageSent, MessageRead)
│   ├── Models/            # Eloquent Models (User, Message)
│   └── Http/Controllers/  # ChatController logic
├── resources/
│   └── js/
│       ├── Components/    # Reusable UI (NavBar, Buttons)
│       ├── Layouts/       # AuthenticatedLayout (The Cyber Shell)
│       └── Pages/         # Vue Views (Chat/Index.vue, Dashboard.vue)
├── routes/
│   ├── web.php            # App Routes
│   └── channels.php       # WebSocket Channel Authorization
└── database/              # SQLite & Migrations
```

-----

## 🤝 Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

1.  Fork the Project
2.  Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3.  Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4.  Push to the Branch (`git push origin feature/AmazingFeature`)
5.  Open a Pull Request

-----

## 📜 License

Distributed under the MIT License. See `LICENSE` for more information.

-----

\<div align="center"\> \<sub\>Built with 💜 & ☕ by \<strong\>ItzShaidSk\</strong\> on \<strong\>Kali Linux\</strong\>\</sub\> \</div\>

---