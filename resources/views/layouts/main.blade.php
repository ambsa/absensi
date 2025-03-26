<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-100 flex">
    @include('partials.sidebar') <!-- Menyertakan sidebar -->
    <div class="flex-1 p-10">
        @yield('content')
    </div>





    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');

        togglePassword.addEventListener('click', function(e) {
            // Toggle the type attribute using a ternary operator
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;

            // Toggle the eye icon based on the password visibility
            if (type === 'password') {
                eyeIcon.classList.remove('fa-eye'); // Remove 'eye' icon
                eyeIcon.classList.add('fa-eye-slash'); // Add 'eye-slash' icon
            } else {
                eyeIcon.classList.remove('fa-eye-slash'); // Remove 'eye-slash' icon
                eyeIcon.classList.add('fa-eye'); // Add 'eye' icon
            }
        });
    </script>

    <script>
        // Ambil elemen yang diperlukan
        const profileImage = document.getElementById('profileImage');
        const dropdownMenu = document.getElementById('dropdownMenu');

        // Menampilkan atau menyembunyikan dropdown menu saat gambar profil diklik
        profileImage.addEventListener('click', function() {
            dropdownMenu.classList.toggle('hidden');
        });

        // Menutup dropdown jika klik di luar elemen dropdown
        document.addEventListener('click', function(event) {
            if (!dropdownMenu.contains(event.target) && !profileImage.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });
    </script>

    <script>
        document.getElementById('logoutButton').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent form submission immediately
            
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will be logged out of your account!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, log out!',
                cancelButtonText: 'Cancel',
                reverseButtons: false,
                customClass: {
                confirmButton: 'bg-red-500 hover:bg-red-600 text-white', // Apply red color to confirm button
                cancelButton: 'bg-gray-500 hover:bg-gray-600 text-white' // Optional: Styling for cancel button
            }
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, submit the form
                    document.getElementById('logoutForm').submit();
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
