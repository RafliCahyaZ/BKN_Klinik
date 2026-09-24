<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# BKN Clinic Management System

Sistem Manajemen Klinik BKN adalah aplikasi berbasis web yang dibangun dengan Laravel untuk mengelola operasional klinik, termasuk pendaftaran pasien, diagnosis, perawatan, dan manajemen obat.

## Fitur Utama

- 📋 Manajemen Pasien (Pendaftaran & Data Pasien)
- 🏥 Manajemen Appointment/Registrasi
- 💊 Manajemen Obat & Stok
- 🩺 Diagnosis & Treatment
- 👥 Manajemen User dengan Role-based Access
- 📊 Log Aktivitas Login
- 🔐 Autentikasi & Autorisasi

## Persyaratan Sistem

Sebelum menginstal aplikasi ini, pastikan sistem Anda memiliki:

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL/PostgreSQL/SQLite
- Web Server (Apache/Nginx) atau PHP built-in server

## Instalasi

### 1. Clone Repository

```bash
git clone <repository-url>
cd BKN-clinic
```

### 2. Install Dependencies

Install PHP dependencies menggunakan Composer:

```bash
composer install
```

Install JavaScript dependencies menggunakan NPM:

```bash
npm install
```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Atur konfigurasi database di file `.env`:

```env
DB_CONNECTION=sqlite
# Atau jika menggunakan MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=bkn_clinic
# DB_USERNAME=root
# DB_PASSWORD=
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Migrasi Database

Jalankan migrasi untuk membuat tabel database:

```bash
php artisan migrate
```

Jika ingin mengisi data dummy, jalankan seeder:

```bash
php artisan db:seed
```

### 6. Buat Storage Link

```bash
php artisan storage:link
```

### 7. Build Assets

Untuk development:

```bash
npm run dev
```

Untuk production:

```bash
npm run build
```

## Menjalankan Aplikasi

### Development Server

Jalankan Laravel development server:

```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

Jika menggunakan `npm run dev`, buka terminal baru dan jalankan bersamaan dengan PHP server.

### Production

Untuk production, konfigurasikan web server (Apache/Nginx) untuk mengarah ke folder `public`.

## Penggunaan

### Login

1. Akses aplikasi melalui browser di `http://localhost:8000`
2. Login menggunakan kredensial yang sudah dibuat atau dari seeder
3. Setelah login, Anda akan diarahkan ke dashboard sesuai role user

### Manajemen Pasien

- Tambah pasien baru melalui menu Pasien
- Edit data pasien yang sudah ada
- Lihat riwayat kunjungan pasien

### Registrasi & Appointment

- Buat appointment baru untuk pasien
- Kelola jadwal kunjungan
- Update status appointment

### Diagnosis & Treatment

- Buat diagnosis untuk pasien
- Tambahkan treatment/perawatan
- Catat resep obat

### Manajemen Obat

- Tambah obat baru ke sistem
- Update stok obat
- Lihat riwayat transaksi stok
- Catat penggunaan obat

## Struktur Proyek

```
app/
├── DTO/                    # Data Transfer Objects
├── Helper/                 # Helper Classes
├── Http/Controllers/       # Controllers
├── Models/                 # Eloquent Models
├── Repository/             # Repository Pattern Implementation
└── Service/                # Business Logic Services

resources/
├── css/                    # Stylesheets
├── js/                     # JavaScript files
└── views/                  # Blade Templates

routes/
├── web.php                 # Web Routes
└── console.php             # Console Commands
```

## Testing

Jalankan test menggunakan PHPUnit:

```bash
php artisan test
```

Atau:

```bash
./vendor/bin/phpunit
```

## Troubleshooting

### Error: Permission Denied

Jika mendapat error permission, berikan akses write ke folder storage dan cache:

```bash
chmod -R 775 storage bootstrap/cache
```

### Error: Class not found

Jalankan autoload dump:

```bash
composer dump-autoload
```

### Cache Issues

Clear semua cache:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## Kontribusi

Jika Anda ingin berkontribusi pada proyek ini:

1. Fork repository
2. Buat branch baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## Lisensi

Proyek ini menggunakan lisensi [MIT](https://opensource.org/licenses/MIT).

## Kontak & Support

Untuk pertanyaan atau dukungan, silakan hubungi tim development atau buat issue di repository.

---

**Built with ❤️ using Laravel**
