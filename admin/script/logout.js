document.getElementById('logoutBtn').addEventListener('click', function(e) {
e.preventDefault(); // cegah langsung logout

Swal.fire({
    title: 'Yakin ingin logout?',
    text: "Anda akan keluar dari akun Anda.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#c5922d',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya, Logout',
    cancelButtonText: 'Batal'
}).then((result) => {
    if (result.isConfirmed) {
    // arahkan ke file logout PHP
    window.location.href = '../config/logout.php';
    }
});
});