Saya memiliki sistem Laravel ERP bernama SmartSort (Bank Sampah Digital).

Sistem ini berbasis POIN yang nilainya setara dengan uang. Sehingga integritas saldo HARUS 100% akurat.

Saya ingin Anda melakukan AUDIT FINANCIAL INTEGRITY MENYELURUH untuk memastikan saldo warga TIDAK PERNAH bocor, salah hitung, atau tidak sinkron.

---

# STRUKTUR SISTEM

Tabel utama:

- users (saldo_poin = cache saldo terkini)
- transactions (header setor sampah)
- transaction_details (rincian item sampah)
- redemptions (header penukaran)
- redemption_details (rincian item reward)
- rewards (master barang)
- waste_categories (master kategori)
- point_ledgers (SUMBER KEBENARAN saldo)

Aturan ledger:

- credit  = poin bertambah (setor sampah)
- debit   = poin berkurang (approve redemption)

---

# TUJUAN AUDIT

✅ Memastikan rumus integritas saldo selalu berlaku:

users.saldo_poin = SUM(credit) - SUM(debit) dari point_ledgers

✅ Memastikan tidak ada saldo yang bocor, double, atau tidak sinkron.
✅ Memastikan setiap mutasi saldo selalu tercatat di ledger.
✅ Memastikan ledger immutable (tidak ada edit/delete).
✅ Memastikan sistem tahan terhadap race condition, double submit, dan retry.
✅ Memastikan semua status redemption memiliki side effect saldo yang BENAR.

---

# AREA YANG HARUS DIPERIKSA

---

## 1. KONSISTENSI MATEMATIS SALDO

Verifikasi rumus utama:

users.saldo_poin == SUM(credit) - SUM(debit)

Untuk SEMUA user.

Skenario:

- user A setor 5 kg sampah, lalu cek saldo
- user A melakukan redemption pending → saldo tidak boleh berkurang
- admin approve → saldo HARUS berkurang
- admin reject → saldo TIDAK boleh berubah
- admin set ready → saldo TETAP
- admin set completed → saldo TETAP
- expire pending → saldo TETAP

Validasi:

❌ Apakah ada user dengan saldo > ledger?
❌ Apakah ada user dengan saldo < ledger?
❌ Apakah ada user dengan saldo bernilai negatif?
❌ Apakah ada saldo yang berubah tanpa ledger entry?

---

## 2. INTEGRITAS LEDGER (IMMUTABILITY)

Cek:

- Apakah ledger bisa di-update?
- Apakah ledger bisa di-delete?
- Apakah ledger memiliki:
  - user_id
  - type (credit/debit)
  - amount
  - reference (transaction_id atau redemption_id)
  - created_at
- Apakah amount selalu > 0?
- Apakah type valid (credit / debit)?

---

## 3. SIDE EFFECT TIAP TRANSAKSI

Setor sampah:

- HARUS ada ledger credit
- HARUS ada increment di users.saldo_poin
- HARUS dalam DB::transaction
- HARUS pakai lockForUpdate

Approve redemption:

- HARUS ada ledger debit
- HARUS ada decrement di users.saldo_poin
- HARUS decrement stok reward
- HARUS dalam DB::transaction
- HARUS pakai lockForUpdate

Reject / Pending / Ready / Completed:

- TIDAK boleh mengubah saldo
- TIDAK boleh menambah ledger entry

---

## 4. RACE CONDITION & DOUBLE SUBMIT

Cek:

- Apakah idempotency_key benar-benar mencegah double credit?
- Apakah lockForUpdate mencegah double debit saat approve?
- Apakah dua admin yang approve bersamaan tidak menyebabkan saldo minus?
- Apakah dua transaksi setor yang dikirim bersamaan tidak menyebabkan saldo double?

Simulasi:

- klik submit 2x cepat
- approve dari 2 device admin sekaligus
- retry network sehingga request dikirim 2 kali

---

## 5. CATATAN HISTORIS

Cek:

- Apakah price_per_kg disimpan sebagai snapshot di transaction_details?
- Apakah point_cost disimpan sebagai snapshot di redemption_details?
- Apakah perubahan harga tidak merusak history?
- Apakah perubahan reward tidak merusak redemption lama?

---

## 6. EDGE CASE FINANSIAL

Cek skenario:

- approve setelah expired
- approve setelah user dihapus
- approve setelah reward dihapus
- claim saat saldo 0
- claim saat stok 0
- claim dengan qty negatif
- claim dengan reward_id orang lain
- approve dua kali dengan retry
- setor sampah dengan berat 0
- setor sampah dengan price_per_kg tidak valid

Apakah ada celah saldo tetap berkurang/bertambah?

---

## 7. KONSISTENSI DASHBOARD WARGA

Cek tampilan:

- saldo poin
- total transaksi
- total penukaran
- total poin dipakai

Validasi:

- Apakah angka di dashboard cocok dengan ledger?
- Apakah pending dianggap "sudah dipotong"?
- Apakah rejected dianggap aktif?

---

## 8. STATUS TRANSITION CHECK

Validasi seluruh state machine:

pending → approved ✅
pending → rejected ✅
approved → ready ✅
ready → completed ✅
approved → rejected ❌
completed → reset ❌
ready → reset ❌

Cek:

- Apakah bisa lompat status?
- Apakah ada status duplikat?
- Apakah status ganda menyebabkan saldo double potong?

---

## 9. STOK REWARD vs LEDGER

Cek konsistensi stok:

- Apakah pengurangan stok hanya saat approve?
- Apakah ada stok minus?
- Apakah stok pernah berubah tanpa transaksi?
- Apakah pengurangan stok dalam DB::transaction?

---

## 10. AUDIT RECONCILIATION (REKONSILIASI)

Cek apakah sistem memiliki:

- command artisan untuk rekonsiliasi saldo
- script audit harian
- mekanisme alert jika mismatch
- log aktivitas ledger
- backup data ledger

Jika tidak ada, sarankan implementasi.

---

# OUTPUT YANG SAYA MAU

1. Status tiap area:
   ✅ Aman
   ⚠️ Perlu perbaikan
   ❌ Bug critical

2. Daftar bug:
   - file
   - lokasi
   - dampak finansial
   - cara mengeksploitasi (jika ada)

3. Validasi rumus:
   - berapa user yang mismatch?
   - berapa selisih (jika ada)?
   - apakah ada ledger yang tidak punya reference?

4. Solusi perbaikan:
   - kode konkret
   - flow yang seharusnya
   - cara rekonsiliasi otomatis

5. Skor finansial:
   - Konsistensi Saldo: %
   - Integritas Ledger: %
   - Race Safety: %
   - Edge Case Handling: %
   - Overall Finansial: %

6. Rekomendasi:
   🔴 wajib segera
   🟡 sangat disarankan
   🟢 improvement

---

# IMPORTANT

- Audit ini menyangkut UANG (poin). Asumsikan setiap user bisa menjadi penyerang.
- Cari bug "silent" (tidak terlihat tapi memengaruhi saldo).
- Cari kemungkinan FRAUD (warga mencuri poin, admin manipulasi data).
- Cari race condition tersembunyi.
- Pastikan SEMUA mutasi saldo TERCATAT di ledger.
- Gunakan Laravel + MySQL best practice.
- Fokus pada masalah nyata, bukan teori.