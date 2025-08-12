<?php
$page_title = "Jasa Inspeksi Gadget";
include 'header.php'; 
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="text-center mb-5">
            <h1 class="display-5">Jasa Inspeksi Profesional</h1>
            <p class="lead text-muted">Ingin membeli gadget bekas dari luar tapi ragu dengan kondisinya? Biarkan saya yang melakukan inspeksi detail untuk Anda. Isi form di bawah untuk memulai.</p>
        </div>

        <div class="card p-4">
            <form id="form-inspeksi">
                <div class="mb-3">
                    <label for="customer_name" class="form-label">Nama Lengkap Anda</label>
                    <input type="text" id="customer_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="customer_contact" class="form-label">Kontak Anda (No. WhatsApp / Email)</label>
                    <input type="text" id="customer_contact" class="form-control" placeholder="Contoh: 081234567890" required>
                </div>
                <div class="mb-3">
                    <label for="device_type" class="form-label">Tipe Gadget yang Akan Diinspeksi</label>
                    <input type="text" id="device_type" class="form-control" placeholder="Contoh: Samsung S22 Ultra 256GB" required>
                </div>
                <div class="mb-3">
                    <label for="notes" class="form-label">Catatan Tambahan (Opsional)</label>
                    <textarea id="notes" rows="4" class="form-control" placeholder="Contoh: Link penjual, hal spesifik yang perlu dicek, lokasi COD, dll."></textarea>
                </div>
                <button type="button" id="kirim-wa-btn" class="btn btn-success w-100">
                    Kirim Permintaan via WhatsApp
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('kirim-wa-btn').addEventListener('click', function() {
    
    const nomorWhatsApp = '6285291815507'; 

    const nama = document.getElementById('customer_name').value;
    const kontak = document.getElementById('customer_contact').value;
    const gadget = document.getElementById('device_type').value;
    const catatan = document.getElementById('notes').value;

    if (nama === '' || kontak === '' || gadget === '') {
        alert('Mohon isi semua kolom yang wajib diisi (Nama, Kontak, Tipe Gadget).');
        return; 
    }

    const pesan = `*Permintaan Jasa Inspeksi - Insect*

Halo, saya ingin menggunakan jasa inspeksi. Berikut detailnya:

*Nama Klien:* ${nama}
*Kontak:* ${kontak}
*Tipe Gadget:* ${gadget}

*Catatan Tambahan:*
${catatan}

Terima kasih.`;

   
    const urlWhatsApp = `https://wa.me/${nomorWhatsApp}?text=${encodeURIComponent(pesan)}`;
    
    
    window.open(urlWhatsApp, '_blank');
});
</script>

<?php include 'footer.php'; ?>