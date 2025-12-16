# 🚀 TaskFlow - Modern Kanban Board

TaskFlow adalah aplikasi manajemen tugas berbasis Kanban (mirip Trello) yang dibangun menggunakan teknologi modern Laravel. Project ini dibuat untuk mendemonstrasikan kemampuan Fullstack Development.

<img width="1919" height="904" alt="image" src="https://github.com/user-attachments/assets/9b8d7bd8-a3e6-4ecb-b4e4-5fbd08474a12" />

## ✨ Fitur Utama
- **Drag & Drop Sorting**: Menggeser kartu antar kolom dengan mulus menggunakan SortableJS.
- **Interactive UI**: Dibangun dengan **Livewire 3** untuk pengalaman pengguna yang reaktif tanpa reload halaman.
- **Image Attachments**: Fitur upload gambar cover pada setiap tugas dengan validasi size client-side.
- **Glassmorphism Design**: Tampilan UI modern menggunakan Tailwind CSS.
- **SweetAlert2 Integration**: Notifikasi dan konfirmasi hapus yang interaktif.
- **CRUD Lengkap**: Create, Read, Update, Delete untuk kartu tugas.
- **Mass Reset**: Fitur untuk menghapus seluruh data board sekaligus.

## 🛠️ Teknologi yang Digunakan
- **Framework**: Laravel 12
- **Frontend**: Livewire 3, Alpine.js, Tailwind CSS
- **Library**: SortableJS, SweetAlert2
- **Database**: MySQL

## 💻 Cara Install di Lokal
1. Clone repository ini:
   ```bash
   git clone https://github.com/ridwanariefm/taskflow-kanban.git
   
2. Masuk ke folder:
     ```bash
   cd taskflow-kanban

4. Install dependencies:
     ```bash
   composer install
   npm install && npm run build

6. Copy file environment:
     ```bash
   cp .env.example .env

8. Generate key & Migrate database:
     ```bash
   php artisan key:generate
   php artisan migrate
   php artisan storage:link

10. Jalankan server:
    ```bash
    php artisan serve

Dibuat dengan ❤️ oleh Ridwan Arief Mutaqin
