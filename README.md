# Laporan Pertemuan 9

Muhammad Zaidan Ahbab 4523210081

## Tugas 2

Tugas 2: Kustomisasi dan Pengembangan (Nilai: 40)

Tambahkan fitur-fitur berikut:

✅ Tambah Field Description pada Book (10 poin)
- Migration : tambah kolom description (text)
- Model : tambah ke `$fillable`
- Resource : tambah Textarea component di form
- Factory : generate description dengan fake()->paragraph()

  <img width="1918" height="1078" alt="image" src="https://github.com/user-attachments/assets/89e6f582-3339-49db-8801-d703111cc397" />


✅ Implementasi Role-Based Access (15 poin)
- Tambah kolom role pada tabel users
- Buat seeder untuk user dengan role berbeda (admin, staff, viewer)
- Update BookPolicy:
   - Admin: semua akses
   - Staff: bisa create, edit, view (tidak bisa delete)
   - Viewer: hanya bisa view

     <img width="1440" height="2838" alt="code" src="https://github.com/user-attachments/assets/c6268377-5d0e-4b78-b52f-502ff7725c42" />


✅ Tambah Widget Dashboard (10 poin)
- Buat widget untuk menampilkan:
   - Total books
   - Total authors
   - Books available vs borrowed (chart/stats)

     <img width="1918" height="1078" alt="image" src="https://github.com/user-attachments/assets/50043742-c20d-4dd2-a009-6bb4b68b970b" />


✅ Tambah Kategori Buku (5 poin)
- Buat model Category
- Relasi many-to-many dengan Book
- Tambah di form book (multiple select)

  <img width="1918" height="1078" alt="image" src="https://github.com/user-attachments/assets/96268806-fda3-42f9-8dc2-f20bc285fb99" />
