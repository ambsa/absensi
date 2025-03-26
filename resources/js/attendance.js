// Pastikan userId sudah didefinisikan di frontend, misalnya dari Blade atau Vue.js
window.Echo.private('attendance.' + userId)
    .listen('AttendanceUpdated', (event) => {
        // Menampilkan alert dengan pesan yang diterima dari server
        alert(event.attendance.status === 'checked_in' ? 'Berhasil Absen Masuk!' : 'Berhasil Pulang!');
    });
