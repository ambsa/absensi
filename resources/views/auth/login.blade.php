<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a329084b4e.js" crossorigin="anonymous"></script>
</head>
<body>
    <body class="bg-gradient-to-r from-gray-800 via-gray-600 to-gray-800 min-h-screen flex items-center justify-center font-sans">

        <div class="w-full max-w-md bg-white/20 backdrop-blur-lg border border-gray-300/30 shadow-xl rounded-xl p-8 text-white">
    
            <!-- Header -->
            {{-- <h2 class="text-3xl font-extrabold text-gray-100 text-center mb-6">Welcome Back!</h2> --}}
            <div class="flex flex-col items-center">
                <img src="{{ asset('assets/images/fyplogo.png') }}" alt="FYP Logo" class="w-32 h-auto mx-auto mb-4">
                <p class="text-sm text-gray-300 text-center mb-6">Login to access your account</p>
            </div>
    
            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf
    
                <!-- Email input -->
                <div class="mb-6">
                    <label for="Name" class="block text-gray-300 text-sm font-bold mb-2">Name</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fa-solid fa-address-card text-gray-400"></i>
                        </span>
                        <input id="name" type="text" placeholder="Enter your name"
                            class="shadow appearance-none border rounded-lg w-full py-2 px-10 text-black leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                            name="name" value="{{ old('name') }}" required autocomplete="username" autofocus>
                    </div>
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
    
                <!-- Password input -->
                <div class="mb-6">
                    <label for="password" class="block text-gray-300 text-sm font-bold mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fa-solid fa-lock text-gray-400"></i>
                        </span>
                        <input id="password" type="password" placeholder="Enter your password"
                            class="shadow appearance-none border rounded-lg w-full py-2 px-10 pr-10 text-black leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                            name="password" required autocomplete="current-password">
                        <button type="button"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer text-gray-400 hover:text-gray-600"
                            onclick="togglePassword()" aria-label="Toggle password visibility" tabindex="0">
                            <i id="password-icon" class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
    
                <!-- Remember Me -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember"
                            class="h-4 w-4 text-blue-500 focus:ring-blue-400 border-gray-300 rounded">
                        <label for="remember" class="ml-2 text-sm text-gray-300">Remember Me</label>
                    </div>
                    <a href="#" class="text-sm text-blue-400 hover:underline">Forgot Password?</a>
                </div>
    
                <!-- Login Button -->
                <div class="flex justify-center">
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-2 px-20 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-purple-500 transform transition duration-200 hover:scale-105">
                        Login
                    </button>
                </div>
            </form>
    
            <!-- Footer -->
            <p class="text-center text-gray-300 text-sm mt-6">
                {{-- Don't have an account? <a href="{{ route('register') }}" class="text-blue-400 hover:underline">Sign Up</a> --}}
            </p>
        </div>
    
        <script>
            function togglePassword() {
                const passwordInput = document.getElementById('password');
                const passwordIcon = document.getElementById('password-icon');
    
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    passwordIcon.classList.remove('fa-eye');
                    passwordIcon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    passwordIcon.classList.remove('fa-eye-slash');
                    passwordIcon.classList.add('fa-eye');
                }
            }
        </script>
</body>
</html>
