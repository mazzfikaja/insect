document.addEventListener('DOMContentLoaded', function() {

    const tombolHapus = document.querySelectorAll('.btn-hapus');

    tombolHapus.forEach(function(tombol) {
        tombol.addEventListener('click', function(event) {
            event.preventDefault(); 
            const konfirmasi = confirm('Apakah Anda yakin ingin menghapus produk ini?');
            if (konfirmasi) {
                window.location.href = tombol.getAttribute('href');
            }
        });
    });

});

document.addEventListener('DOMContentLoaded', function() {
    const waBubble = document.getElementById('wa-bubble-btn');
    if (waBubble) {
        const nomorWhatsApp = '6285291815507';
        const pesan = 'Halo Insect, saya ingin bertanya tentang produk atau request untuk dicarikan gadget.';
        const urlWhatsApp = `https://wa.me/${nomorWhatsApp}?text=${encodeURIComponent(pesan)}`;
        waBubble.setAttribute('href', urlWhatsApp);
    }

});