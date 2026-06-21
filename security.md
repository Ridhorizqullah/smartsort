Saya memiliki sistem Laravel ERP bernama SmartSort (Bank Sampah Digital).

Sistem ini memiliki:
- Role: admin, petugas, warga
- Service Layer: TransactionService, RedemptionService
- Database: users, transactions, transaction_details, redemptions, redemption_details, rewards, waste_categories, point_ledgers
- Fitur utama:
  • Setor sampah (admin POS)
  • Penukaran reward (warga klaim)
  • Approval system (pending → approved → ready → completed)
  • Audit trail via PointLedger

Saya menemukan beberapa bug logika di sistem (contoh: status approved tapi saldo poin warga tidak berkurang).

Saya ingin Anda melakukan AUDIT MENYELURUH terhadap LOGIKA bisnis sistem.

---

# TUJUAN

✅ Memastikan seluruh flow sistem bekerja sesuai bisnis logic.
✅ Mendeteksi bug logika tersembunyi.
✅ Memastikan saldo poin, stok, dan ledger SELALU sinkron.
✅ Memastikan setiap status memiliki side-effect yang benar.
✅ Memastikan tidak ada celah duplikasi / saldo tidak sinkron.

---

# AREA YANG HARUS DICEK

---

## 1. FLOW SETOR SAMPAH (TRANSAKSI)

Cek:

- Apakah subtotal_point dihitung benar:
  weight × price_per_kg
- Apakah total_point sesuai SUM(subtotal_point)
- Apakah saldo user bertambah otomatis
- Apakah ledger CREDIT dibuat
- Apakah idempotency mencegah double submit
- Apakah DB::transaction membungkus seluruh proses

Cari bug:

❌ saldo bertambah double
❌ subtotal tidak akurat (float error)
❌ ledger tidak dibuat
❌ transaksi tersimpan tetapi saldo tidak update

---

## 2. FLOW PENUKARAN (REDEMPTION)

Cek seluruh lifecycle:

pending → approved → ready → completed
pending → rejected

Verifikasi pada setiap status:

a. PENDING
- saldo TIDAK boleh berkurang
- stok TIDAK boleh berkurang

b. APPROVED
- saldo HARUS berkurang
- stok HARUS berkurang
- ledger DEBIT dibuat
- approved_at terisi
- tanggal_ambil terisi

c. REJECTED
- saldo TIDAK berubah
- stok TIDAK berubah
- rejected_at terisi

d. READY
- status berubah ke ready
- ready_at terisi
- saldo & stok tetap (sudah dipotong di approved)

e. COMPLETED
- status berubah ke completed
- completed_at terisi
- saldo & stok tetap

Cari bug:

❌ approved tapi saldo tidak berkurang
❌ approved tapi stok tidak berkurang
❌ rejected tapi saldo ikut berkurang
❌ ready muncul tapi belum melalui approved
❌ completed muncul tapi belum ready
❌ status bisa loncat / mundur

---

## 3. INTEGRITAS SALDO (CRITICAL)

Cek:

saldo_poin di tabel users
HARUS = SUM(credit) - SUM(debit) di point_ledgers

Buat skenario:

- warga setor 5kg
- warga claim reward
- admin approve
- admin reject
- admin ready
- admin completed

Lalu validasi:

- saldo akhir
- jumlah credit di ledger
- jumlah debit di ledger

Apakah cocok? Apakah ada mismatch?

---

## 4. INTEGRITAS STOK

Cek:

stock di tabel rewards
HARUS konsisten setiap transaksi approved

Verifikasi:

- stok berkurang saat approved
- stok tidak berkurang saat pending
- stok tidak berkurang saat rejected
- stok tidak bisa minus

---

## 5. CONCURRENCY (RACE CONDITION)

Cek:

- penggunaan lockForUpdate
- penggunaan DB::transaction
- penggunaan idempotency_key

Skenario:

- klik approve 2 kali bersamaan
- klaim 2 kali sangat cepat
- admin & warga akses bersamaan

Apakah saldo / stok bisa minus / double?

---

## 6. ROLE & AUTHORIZATION

Cek logika:

- warga tidak bisa akses /admin
- petugas tidak bisa kelola user / kategori / reward
- admin penuh
- middleware role bekerja
- FormRequest authorize() benar

Skenario:

- warga akses /admin/users → harus 403
- petugas akses /admin/users → harus 403
- warga manipulasi reward_id orang lain
- warga modifikasi qty via DevTools

---

## 7. VALIDATION (BACKEND)

Cek:

- weight > 0
- qty > 0
- saldo cukup sebelum claim
- stok cukup sebelum approve
- nik 16 digit
- idempotency_key UUID
- format request sesuai

Cari bypass:

❌ qty negatif
❌ qty melebihi stok
❌ claim tanpa saldo
❌ submit ganda
❌ XSS / injection

---

## 8. STATUS TRANSITION

Buat state machine:

pending → approved ✅
pending → rejected ✅
approved → ready ✅
ready → completed ✅
approved → rejected ❌ (boleh kah?)
rejected → approved ❌
completed → reset ❌

Cek:

- apakah ada validasi transition?
- apakah bisa lompat status?

---

## 9. EDGE CASES

Cek:

- approve setelah expired
- claim saat stok 0
- claim saat saldo 0
- delete user yang memiliki transaksi
- delete kategori yang masih dipakai
- approve redemption yang sudah dihapus

---

## 10. LEDGER & AUDIT

Cek:

- setiap perubahan saldo selalu tercatat di ledger
- ledger immutable (tidak boleh diubah/dihapus)
- ledger memiliki reference (transaction_id / redemption_id)
- ledger bisa dipakai untuk rekonsiliasi saldo

---

# OUTPUT YANG SAYA MAU

1. Status logika tiap area:
   ✅ Aman
   ⚠️ Perlu perbaikan
   ❌ Bug critical

2. Daftar bug yang ditemukan:
   - lokasi file
   - jenis bug
   - dampak

3. Solusi perbaikan:
   - kode service yang benar
   - flow yang seharusnya

4. Skor logika akhir:
   - Setor: %
   - Redemption: %
   - Authorization: %
   - Integrity: %

5. Rekomendasi tindakan:
   🔴 wajib segera
   🟡 disarankan
   🟢 improvement

---

# IMPORTANT

- Fokus pada LOGIKA bisnis, bukan UI.
- Validasi seluruh flow seolah-olah sistem ini menyangkut UANG.
- Cari bug tersembunyi (silent failure).
- Cari kemungkinan fraud/manipulasi.
- Gunakan Laravel best practice.