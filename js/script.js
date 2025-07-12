document.addEventListener("DOMContentLoaded", function () {
  console.log("script loaded");
  feather.replace();

  // Toggle menu hamburger
  const navbarNav = document.querySelector('.navbar-nav');
  const hamburger = document.querySelector('#hamburger-menu');
  if (hamburger && navbarNav) {
    hamburger.onclick = () => {
      navbarNav.classList.toggle('active');
    };

    document.addEventListener('click', function (e) {
      if (!hamburger.contains(e.target) && !navbarNav.contains(e.target)) {
        navbarNav.classList.remove('active');
      }
    });
  }

// Modal Produk Detail
const itemDetailModal = document.querySelector('#item-detail-modal');
const modalContentContainer = document.getElementById('modal-detail-content');
const closeIcon = document.querySelector('.close-icon');

if (closeIcon && itemDetailModal) {
  closeIcon.addEventListener('click', function (e) {
    e.preventDefault();
    itemDetailModal.style.display = 'none';
  });

  window.onclick = (e) => {
    if (e.target === itemDetailModal) {
      itemDetailModal.style.display = 'none';
    }
  };
}

// Buka modal detail produk
document.querySelectorAll('.item-detail-button').forEach(btn => {
  btn.addEventListener('click', function (e) {
    e.preventDefault();
    const id = this.dataset.id;

    fetch('get_product.php?id=' + id)
      .then(res => res.json())
      .then(data => {
        // Validasi dan escape data
        const fileName = encodeURIComponent(data.gambar || '');
        const namaProduk = (data.nama_produk || '').replace(/</g, "&lt;").replace(/>/g, "&gt;");
        const deskripsi = (data.deskripsi || '').replace(/</g, "&lt;").replace(/>/g, "&gt;");
        const harga = parseInt(data.harga || 0);

        const modalContent = `
          <div class="product-image-detail" style="display: flex; justify-content: center; align-items: center; gap: 20px;">
            <img src="/asset/menu/${fileName}" alt="${namaProduk}" style="border-radius: 10px; max-width: 250px;">
            <div class="product-content" style="display:flex; flex-direction:column; width: 100%; gap: min(20px, 2vw);">
              <div>
                <h3 style="color: black; font-size: 1.5rem;">${namaProduk}</h3>
                <p style="color: black;">${deskripsi}</p>
              </div>
              <br>
              <div class="product-price" style="color: black; display: flex; justify-content: space-between;">
                IDR ${harga.toLocaleString('id-ID')}
                <div class="product-stock" style="color: black;  background: var(--primary-peach); width: fit-content; padding: 5px 10px; border-radius: 5px;">
                  <a href="#" class="add-cart-btn" data-id="${data.id_produk}" style="color: black;" >
                    <i data-feather="shopping-cart"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        `;

        modalContentContainer.innerHTML = modalContent;
        feather.replace();
        itemDetailModal.style.display = 'flex';
        bindAddToCartModal(); // bind ulang tombol di dalam modal
      })
      .catch(err => {
        console.error('Gagal mengambil detail produk:', err);
        alert('Terjadi kesalahan saat menampilkan detail produk.');
      });
  });
});


  // Toast Notifikasi
  const toast = document.getElementById('cart-toast');
  function showToast() {
    if (!toast) return;
    toast.classList.add('show');
    setTimeout(() => {
      toast.classList.remove('show');
    }, 2000);
  }

  // Modal Tambah ke Keranjang
  function showAddCartModal(data) {
    const modal = document.getElementById('add-cart-modal');
    const modalContent = document.getElementById('add-cart-content');
    if (!modal || !modalContent) return;

    const harga = parseInt(data.harga);

    modalContent.innerHTML = `
      <h3 style="color: black; font-size: 1.5rem;">${data.nama_produk}</h3>
      <p style="color: black; font-size: 1rem; margin:0; padding:10px 0px;">Stok tersedia: ${data.stok}</p>
      <div class="qty-control">
        <button id="minus" style="background: white; padding:0 6px; margin:0; background:var(--primary-peach); border-radius: 5px;">-</button>
        <input style="background: white; padding:0 5px; margin:0; border: 2px solid rgb(170, 170, 170); border-radius: 5px; width: 10%" type="number" id="qty" value="1" min="1" max="${data.stok} ">
        <button id="plus" style="background: white; padding:0 6px; margin:0; background:var(--primary-peach); border-radius: 5px;">+</button>
      </div>
      <p style="color:black; font-size:1.5rem; margin:0; padding:10px 0rem;">Total: <span id="total-harga">IDR ${harga.toLocaleString('id-ID')}</span></p>
      <button class="btn" style="background: var(--primary-peach); width: fit-content; padding: 5px 10px; border-radius: 5px;" id="confirm-add" data-id="${data.id_produk}" data-harga="${harga}"> <i data-feather="shopping-cart"></i></button>
    `;

    modal.style.display = 'flex';
    feather.replace();

    const qtyInput = modalContent.querySelector('#qty');
    const totalEl = modalContent.querySelector('#total-harga');
    const minusBtn = modalContent.querySelector('#minus');
    const plusBtn = modalContent.querySelector('#plus');

    function updateTotal() {
      let qty = parseInt(qtyInput.value);
      if (isNaN(qty) || qty < 1) qty = 1;
      if (qty > data.stok) qty = data.stok;
      qtyInput.value = qty;
      const total = harga * qty;
      totalEl.textContent = `IDR ${total.toLocaleString('id-ID')}`;
    }

    if (minusBtn && plusBtn && qtyInput) {
      minusBtn.onclick = () => {
        if (qtyInput.value > 1) qtyInput.value--;
        updateTotal();
      };

      plusBtn.onclick = () => {
        if (qtyInput.value < data.stok) qtyInput.value++;
        updateTotal();
      };

      qtyInput.oninput = updateTotal;
    }

    const confirmBtn = modalContent.querySelector('#confirm-add');
    if (confirmBtn) {
      confirmBtn.onclick = () => {
        const jumlah = qtyInput.value;
        window.location.href = `tambah_keranjang.php?id=${data.id_produk}&jumlah=${jumlah}`;
      };
    }
  }

  // Tombol tutup modal keranjang
  const closeCartIcon = document.querySelector('.close-cart-icon');
  if (closeCartIcon) {
    closeCartIcon.onclick = (e) => {
      e.preventDefault();
      document.getElementById('add-cart-modal').style.display = 'none';
    };
  }

  // Re-bind tombol "add to cart" di modal detail
  function bindAddToCartModal() {
    const addBtn = document.querySelector('.add-cart-btn');
    if (addBtn) {
      addBtn.addEventListener('click', function (e) {
        e.preventDefault();
        const id = this.dataset.id;

        fetch('get_product.php?id=' + id)
          .then(res => res.json())
          .then(data => {
            showAddCartModal(data);
          });
      });
    }
  }

  // Tombol "add to cart" langsung di kartu produk
  document.querySelectorAll('.add-cart-direct').forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      const id = this.dataset.id;

      fetch('get_product.php?id=' + id)
        .then(res => res.json())
        .then(data => {
          showAddCartModal(data);
        });
    });
  });
});
