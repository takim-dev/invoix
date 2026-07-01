<?= $this->extend('layouts/site') ?>
<?= $this->section('title') ?><?= esc($title) ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .page-hero {
        padding: 3rem 0 2rem;
        background: linear-gradient(135deg, var(--brand-soft) 0%, rgba(201, 168, 76, 0.08) 100%);
        border-bottom: 1px solid var(--bs-border-color);
    }
    .page-hero h1 {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 800;
        letter-spacing: -0.02em;
        margin: 0;
        background: linear-gradient(135deg, var(--brand) 0%, var(--brand-gold) 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    .help-section { padding: 3rem 0; }
    .help-section h3 {
        font-weight: 700;
        margin-bottom: 1rem;
        font-size: 1.4rem;
    }
    .help-section h3 i {
        color: var(--brand);
        margin-right: 0.5rem;
    }
    .step-list {
        list-style: none;
        padding: 0;
        counter-reset: step;
    }
    .step-list li {
        counter-increment: step;
        position: relative;
        padding-left: 3rem;
        margin-bottom: 1.8rem;
    }
    .step-list li::before {
        content: counter(step);
        position: absolute;
        left: 0;
        top: 0;
        width: 32px;
        height: 32px;
        background: var(--brand);
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.9rem;
        box-shadow: 0 6px 14px rgba(27, 58, 92, 0.30);
    }
    .step-list li h5 {
        font-weight: 700;
        margin-bottom: 0.3rem;
    }
    .step-list li p {
        color: var(--muted);
        margin: 0;
        font-size: 0.95rem;
        line-height: 1.6;
    }
    .tip-card {
        background: var(--bs-body-bg);
        border: 1px solid var(--bs-border-color);
        border-radius: 12px;
        padding: 1.4rem;
        margin-bottom: 1rem;
        display: flex;
        gap: 1rem;
        align-items: flex-start;
    }
    .tip-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: var(--brand-soft);
        color: var(--brand);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .tip-card h6 {
        font-weight: 700;
        margin-bottom: 0.2rem;
    }
    .tip-card p {
        color: var(--muted);
        margin: 0;
        font-size: 0.9rem;
        line-height: 1.5;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<header class="page-hero">
    <div class="container">
        <h1><?= esc($title) ?></h1>
    </div>
</header>

<section class="help-section">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                <h3><i class="bi bi-list-ol"></i> Langkah-langkah Membuat Invoice</h3>
                <ol class="step-list">
                    <li>
                        <h5>Siapkan Data Perusahaan</h5>
                        <p>Pastikan profil perusahaan sudah diisi lengkap di menu <strong>Companies</strong>. Data seperti nama, alamat, email, dan logo akan otomatis muncul di invoice.</p>
                    </li>
                    <li>
                        <h5>Tambahkan Item / Produk</h5>
                        <p>Buka menu <strong>Items</strong> dan tambahkan produk atau jasa yang ingin dijual. Isi nama, harga satuan, dan pilih mata uang (currency) yang sesuai.</p>
                    </li>
                    <li>
                        <h5>Buka Halaman Create Invoice</h5>
                        <p>Klik menu <strong>Invoices</strong> lalu tekan tombol <strong>New Invoice</strong>. Kamu akan diarahkan ke formulir pembuatan invoice.</p>
                    </li>
                    <li>
                        <h5>Isi Detail Invoice</h5>
                        <p>Pilih perusahaan, client, tanggal invoice, tanggal jatuh tempo (due date), currency, dan tax rate. Nomor invoice akan digenerate otomatis.</p>
                    </li>
                    <li>
                        <h5>Pilih Item & Jumlah</h5>
                        <p>Pilih item dari dropdown, isi quantity, dan harga akan terisi otomatis. Kamu bisa menambah beberapa baris item sekaligus dengan tombol <strong>+</strong>.</p>
                    </li>
                    <li>
                        <h5>Simpan & Download</h5>
                        <p>Setelah semua terisi, klik <strong>Save Invoice</strong>. Invoice akan tersimpan dan kamu bisa melihat preview, mengedit status, mendownload PDF, atau mencetaknya.</p>
                    </li>
                </ol>
            </div>

            <div class="col-lg-4">
                <h3><i class="bi bi-lightbulb"></i> Tips & Trik</h3>

                <div class="tip-card">
                    <span class="tip-icon"><i class="bi bi-currency-exchange"></i></span>
                    <div>
                        <h6>Pilih Currency yang Tepat</h6>
                        <p>Gunakan currency yang sesuai dengan client kamu. Tersedia USD, IDR, MYR, CNY, INR, EUR, SAR, dan VND.</p>
                    </div>
                </div>

                <div class="tip-card">
                    <span class="tip-icon"><i class="bi bi-hash"></i></span>
                    <div>
                        <h6>Nomor Invoice Otomatis</h6>
                        <p>Nomor invoice digenerate otomatis dengan format <code>INV-XXX-COMP-YYYY-NNNN</code>. Tidak perlu diisi manual.</p>
                    </div>
                </div>

                <div class="tip-card">
                    <span class="tip-icon"><i class="bi bi-file-earmark-pdf"></i></span>
                    <div>
                        <h6>Export ke PDF</h6>
                        <p>Setiap invoice bisa didownload sebagai file PDF siap kirim ke client dengan sekali klik.</p>
                    </div>
                </div>

                <div class="tip-card">
                    <span class="tip-icon"><i class="bi bi-arrow-repeat"></i></span>
                    <div>
                        <h6>Update Status Invoice</h6>
                        <p>Status invoice bisa diubah kapan saja: Draft → Sent → Paid → Cancelled. Pantau status dari halaman daftar invoice.</p>
                    </div>
                </div>

                <div class="tip-card">
                    <span class="tip-icon"><i class="bi bi-database"></i></span>
                    <div>
                        <h6>Export Data ke Excel</h6>
                        <p>Gunakan tombol <strong>Download Excel</strong> di halaman Invoices untuk mengekspor semua data invoice ke spreadsheet.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
