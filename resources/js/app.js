import './bootstrap';


if ('serviceWorker' in navigator) {
    console.log('Browser mendukung Service Worker');
} else {
    console.log('Browser TIDAK mendukung Service Worker');
}
// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getMessaging, getToken } from "firebase/messaging";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
    apiKey: "AIzaSyD9MGAG1duPQRIwAEhKTbyDjmcDxYK-31I",
    authDomain: "absensi-f39aa.firebaseapp.com",
    projectId: "absensi-f39aa",
    storageBucket: "absensi-f39aa.firebasestorage.app",
    messagingSenderId: "422165205319",
    appId: "1:422165205319:web:876a3ff99c744b55ddbc62",
    measurementId: "G-RC7X4R5E2Y",
};

// Inisialisasi Firebase App
const app = initializeApp(firebaseConfig);
// Inisialisasi Firebase Messaging
const messaging = getMessaging(app);

// Minta izin untuk menerima notifikasi
Notification.requestPermission()
    .then(() => {
        return getToken(messaging, {
            vapidKey:
                "BLysGgZhOJchLuoKU7HUAmfOK2eGsUCV8v2Y5_6jDezg5r0avvaBlJBpnN1tkbvAJXVA8_98jRAgea2swH-i1QM",
        });
    })
    .then((token) => {
        console.log("Device Token:", token);

        // Kirim token ke server Laravel
        fetch("/store-device-token", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
            },
            body: JSON.stringify({ device_token: token }),
        })
            .then((response) => response.json())
            .then((data) => {
                console.log("Token berhasil disimpan:", data);
            })
            .catch((error) => {
                console.error("Gagal menyimpan token:", error);
            });
    })
    .catch((err) => {
        console.error("Error getting token:", err);
    });
