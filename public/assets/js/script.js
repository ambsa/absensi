document.addEventListener("DOMContentLoaded", () => {
    // Fungsi untuk show password
    const togglePassword = document.getElementById("togglePassword");
    const passwordField = document.getElementById("password");
    const eyeIcon = document.getElementById("eye-icon");

    if (togglePassword && passwordField && eyeIcon) {
        togglePassword.addEventListener("click", function (e) {
            const type =
                passwordField.type === "password" ? "text" : "password";
            passwordField.type = type;

            if (type === "password") {
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        });
    }

    // UNTUK DROPDOWN PADA MENU PROFIL
    $(document).ready(function () {
        // Ambil elemen-elemen yang diperlukan
        const profileImage = $('#profileImage');
        const profileDropdownMenu = $('#profileDropdownMenu');
    
        if (profileImage.length && profileDropdownMenu.length) {
            // Toggle dropdown ketika gambar profil diklik
            profileImage.on('click', function (e) {
                e.stopPropagation(); // Mencegah event bubbling ke document
    
                profileDropdownMenu.toggleClass('opacity-0 invisible scale-95');
            });
    
            // Sembunyikan dropdown jika pengguna mengklik di luar area dropdown
            $(document).on('click', function (e) {
                if (!profileImage.is(e.target) && !profileDropdownMenu.is(e.target) && profileDropdownMenu.has(e.target).length === 0) {
                    profileDropdownMenu.addClass('opacity-0 invisible scale-95');
                }
            });
    
            // Mencegah dropdown tertutup saat mengklik di dalam dropdown
            profileDropdownMenu.on('click', function (e) {
                e.stopPropagation();
            });
        }
    });

    // SweetAlert untuk konfirmasi logout
    const logoutButton = document.getElementById("logoutButton");
if (logoutButton) {
    logoutButton.addEventListener("click", function (event) {
        event.preventDefault();

        Swal.fire({
            title: "Are you sure?",
            text: "You will be logged out of your account!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, log out!",
            cancelButtonText: "Cancel",
            customClass: {
                confirmButton: "bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-3", // Styling tombol konfirmasi
                cancelButton: "bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded", // Styling tombol batal
            },
            buttonsStyling: false, // Nonaktifkan styling bawaan SweetAlert2
            background: '#1E293B', // Latar belakang gelap
            color: '#ffffff',     // Teks putih
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("logoutForm").submit();
            }
        });
    });
}

    // DROPDOWN PADA SUBMENU PENGAJUAN
    $(document).ready(function () {
        // Toggle dropdown ketika menu utama diklik
        $("#pengajuanMenu").on("click", function (e) {
            e.stopPropagation(); // Mencegah event bubbling ke document

            const dropdown = $("#pengajuanDropdownContent");

            if (dropdown.hasClass("hidden")) {
                // Tampilkan dropdown
                dropdown.removeClass("hidden");
                setTimeout(() => {
                    dropdown.removeClass("opacity-0").addClass("opacity-100");
                }, 10); // Delay kecil untuk memastikan animasi berjalan
            } else {
                // Sembunyikan dropdown
                dropdown.removeClass("opacity-100").addClass("opacity-0");
                setTimeout(() => {
                    dropdown.addClass("hidden");
                }, 300); // Sesuaikan dengan durasi transisi (300ms)
            }
        });

        // Sembunyikan dropdown jika pengguna mengklik di luar area dropdown
        $(document).on("click", function () {
            const dropdown = $("#pengajuanDropdownContent");
            if (!dropdown.hasClass("hidden")) {
                dropdown.removeClass("opacity-100").addClass("opacity-0");
                setTimeout(() => {
                    dropdown.addClass("hidden");
                }, 300);
            }
        });

        // Mencegah dropdown tertutup saat mengklik di dalam dropdown
        $("#pengajuanDropdownContent").on("click", function (e) {
            e.stopPropagation();
        });
    });
});
