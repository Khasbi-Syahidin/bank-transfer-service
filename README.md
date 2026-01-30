## Asumsi Teknis

- Setiap bank diasumsikan memiliki kontrak API yang berbeda, sehingga masing-masing bank memiliki interface dan client sendiri.
- Implementasi API bank menggunakan fake API class untuk simulasi response.
- Mekanisme fallback bank digunakan jika transfer online gagal.
- Penjadwalan transfer ditentukan oleh aturan waktu dan mata uang, bukan waktu eksekusi aktual.
- Seluruh transaksi (SUCCESS, FAILED, PENDING) dicatat ke database.
- Pengecekan status transfer diambil langsung dari database.
- Transfer ID dianggap unik dan dikirim dari client.
- Testing menggunakan mock dan fake implementation, tanpa dependency ke external service.

## Endpoint API

- **`GET /api/banks`**:
- **`POST /api/transfers/execute`**:
- **`GET /api/transfers/schedule`**:
- **`GET /api/transfers/{transferId}/status`**:

## Run Tes

```
php artisan test
```

## Demo API

```
https://apibank.khasbi.my.id/api
```
