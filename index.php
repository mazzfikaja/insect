<?php
$page_title = "Selamat Datang";
include 'header.php';

$all_products = getAllProducts($mysqli, '');
$featured_products = array_slice($all_products, 0, 3);
$announcements = getActiveAnnouncements($mysqli, 5);
?>

<div class="hero-section text-center p-5 mb-4 rounded-3">
    <h1 class="display-4">INSECT</h1>
    <p class="fs-4 text-accent fw-bold">Lihat Detailnya, Dapatkan Kualitasnya.</p>
    <p class="col-lg-8 mx-auto text-muted">Marketplace terpercaya untuk gadget bekas pilihan yang telah melewati inspeksi detail dan transparan.</p>
</div>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Pengumuman & Info Terbaru</h2>
        <a href="pengumuman.php" class="btn btn-sm btn-outline-secondary">Lihat Lainya &rarr;</a>
    </div>
    
    <div class="swiper announcement-slider">
        <div class="swiper-wrapper pb-3">
            <?php foreach ($announcements as $item): ?>
            <div class="swiper-slide">
                <div class="card h-100 d-flex flex-column">
                    <?php if (!empty($item['image']) && $item['image'] !== 'default.jpg' && file_exists('assets/images/' . $item['image'])): ?>
                        <img src="assets/images/<?php echo e($item['image']); ?>" class="card-img-top" alt="<?php echo e($item['title']); ?>" style="aspect-ratio: 16/9; object-fit: cover;">
                    <?php else: ?>
                        <div class="product-image-placeholder" style="aspect-ratio: 16/9;">
                            <span class="text-muted small">Tidak Ada Gambar</span>
                        </div>
                    <?php endif; ?>

                    <div class="card-body">
                        <p class="small text-muted mb-1"><?php echo date('d F Y', strtotime($item['created_at'])); ?></p>
                        <h6 class="card-title fw-bold"><?php echo e($item['title']); ?></h6>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0 text-center">
                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                data-bs-toggle="modal" 
                                data-bs-target="#announcementModal"
                                data-title="<?php echo e($item['title']); ?>"
                                data-content="<?php echo e(nl2br($item['content'])); ?>"
                                data-image="<?php echo e($item['image']); ?>">
                            Lihat Detail
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
</div>

<h2 class="text-center mb-4 mt-5">Baru Ditambahkan</h2>
<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php if (!empty($featured_products)):?><?php foreach ($featured_products as $product):?><div class="col"><div class="card h-100 product-card"><?php if (!empty($product['main_image']) && $product['main_image'] !== 'default.jpg' && file_exists('assets/images/' . $product['main_image'])): ?><img src="assets/images/<?php echo e($product['main_image']); ?>" class="card-img-top" alt="<?php echo e($product['model']); ?>"><?php else: ?><div class="product-image-placeholder"><span class="text-muted">Tidak Ada Gambar</span></div><?php endif; ?><div class="card-body d-flex flex-column"><h5 class="card-title"><?php echo e($product['brand'] . ' ' . $product['model']); ?></h5><p class="card-text text-accent fw-bold fs-5"><?php echo formatRupiah($product['price']); ?></p><a href="produk.php?id=<?php echo e($product['id']); ?>" class="btn btn-outline-accent mt-auto">Lihat Detail</a></div></div></div><?php endforeach; ?><?php else: ?><p class="text-center text-muted">Belum ada produk yang dijual saat ini.</p><?php endif; ?>
</div>

<div class="text-center py-5">
    <a href="marketplace.php" class="btn btn-accent btn-lg">Lihat Semua Produk</a>
</div>

<div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="announcementModalLabel">Detail Pengumuman</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img src="" id="announcementModalImage" class="img-fluid rounded mb-3" alt="Gambar Pengumuman" style="display: none;">
        <div id="announcementModalBody"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>

<script>
    const swiper = new Swiper('.announcement-slider', {
        loop: false,
        spaceBetween: 20,
        mousewheel: {
            forceToAxis: true,
        },
        breakpoints: {
            576: { slidesPerView: 2 },
            992: { slidesPerView: 3 }
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });

    const announcementModal = document.getElementById('announcementModal');
    announcementModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const title = button.getAttribute('data-title');
        const content = button.getAttribute('data-content');
        const image = button.getAttribute('data-image');
        const modalTitle = announcementModal.querySelector('.modal-title');
        const modalBody = announcementModal.querySelector('#announcementModalBody');
        const modalImage = announcementModal.querySelector('#announcementModalImage');
        modalTitle.textContent = title;
        modalBody.innerHTML = content;
        if (image && image !== 'default.jpg') {
            modalImage.src = 'assets/images/' + image;
            modalImage.style.display = 'block';
        } else {
            modalImage.style.display = 'none';
        }
    });
</script>