
# ⚡ Kali Chat Messenger - By SHAID SK.
### The Ultimate Cyber-SaaS Real-Time Communication Platform

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Vue.js](https://img.shields.io/badge/Vue.js-3-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Reverb](https://img.shields.io/badge/Reverb-WebSockets-orange?style=for-the-badge)
![License](https://img.shields.io/badge/License-MIT-blue?style=for-the-badge)

<div align="center">
  <img src="Kali-chat-demo.gif" alt="App Demo" width="100%">
  <br><br>
  <p>
    <strong>Zero Latency. Infinite Style. Total Security.</strong>
  </p>
  <p>
    A high-performance chat application featuring a <strong>Cyber-SaaS aesthetic</strong>, 
    <strong>Holographic UI</strong>, and <strong>3D Interactive Elements</strong>.
  </p>
  <br />
  <a href="#-installation"><strong>Explore the Docs »</strong></a>
  ·
  <a href="https://github.com/ShAiDSk/kali_chat-messenger/issues">Report Bug</a>
  ·
  <a href="https://github.com/ShAiDSk/kali_chat-messenger/issues">Request Feature</a>
</div>

---

## 🔮 Overview

**Kali Chat Messenger** (powered by Reverb) redefines the chat experience. Built on the bleeding edge of **Laravel 11**, it leverages **Laravel Reverb** for blazing-fast WebSocket communication without external dependencies.

The frontend is a masterpiece of **Vue 3 (Composition API)** and **Tailwind CSS**, featuring a deep-space cyberpunk aesthetic with real-time physics-based animations, spring-loaded message bubbles, and reactive lighting effects.

---

## 🚀 Key Features

### **✨ Interactive UI/UX**
* **Supreme Glassmorphism:** Multilayered frosted glass panels (`backdrop-blur-xl`) with reactive borders.
* **3D Parallax Tilt:** Login/Register cards physically react to mouse movement.
* **Spring Physics:** Messages "pop" in with organic, bouncy animations.
* **Living Backgrounds:** Animated nebula orbs and scanning cyber-grids that move with the user.
* **Holographic Hovers:** Images and cards glow with a spectral interference effect on interaction.

### **⚡ Core Functionality**
* **Real-Time Sync:** Instant message delivery via Secure WebSockets.
* **Live Presence:** Real-time "Online/Offline" status with pulsing beacons.
* **Smart Typing:** Reactive typing indicators that glow when active.
* **Read Receipts:** WhatsApp-style status ticks (Grey = Sent, Green = Read).
* **Secure Channels:** Private, encrypted channels for every conversation.
* **Media Sharing:** Drag-and-drop image uploads with instant previews.

---

## 🛠️ Tech Stack

| Component | Technology | Description |
| :--- | :--- | :--- |
| **Backend** | Laravel 11 | The PHP Framework for Web Artisans. |
| **Real-Time** | Laravel Reverb | First-party WebSocket server for Laravel. |
| **Frontend** | Vue 3 | The Progressive JavaScript Framework (Composition API). |
| **Routing** | Inertia.js | Modern monolith architecture (No API required). |
| **Styling** | Tailwind CSS | Utility-first CSS framework. |
| **Database** | SQLite | Serverless, zero-configuration database. |

---

## ⚡ Installation

Follow these steps to deploy the system on your local machine.

### **Prerequisites**
* PHP 8.2+
* Node.js & NPM
* Composer

### **1. Clone the Repository**
```bash
git clone [https://github.com/ShAiDSk/kali_chat-messenger.git](https://github.com/ShAiDSk/kali_chat-messenger.git)
cd kali_chat-messenger
```

### **2. Install Dependencies**

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### **3. Environment Setup**

```bash
cp .env.example .env
php artisan key:generate
```

**Configure SQLite:**
Open `.env` and update the database section:

```env
DB_CONNECTION=sqlite
# Remove other DB_ lines
```

*Create the database file:*

```bash
touch database/database.sqlite
```

**Configure Reverb (WebSockets):**
Add this to your `.env` to enable real-time features:

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

### **4. Finalize Setup**

```bash
php artisan migrate
php artisan storage:link
```

-----

## 🖥️ Running the App

You need **3 terminals** running simultaneously to power the application.

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

## 🧩 Directory Structure

```
kali_chat-messenger/
├── app/
│   ├── Events/            # WebSocket Events (MessageSent, MessageRead)
│   ├── Models/            # Eloquent Models (User, Message)
│   └── Http/Controllers/  # ChatController logic
├── resources/
│   └── js/
│       ├── Components/    # UI Components (NavBar, Buttons)
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

\<div align="center"\>
\<sub\>Built with 💜 & ☕ by \<strong\>\<a href="https://www.google.com/search?q=https://github.com/ShAiDSk"\>ShAiDSk\</a\>\</strong\> on \<strong\>Kali Linux\</strong\>\</sub\>
\</div\>

