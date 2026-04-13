<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">

        <!-- Title -->
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">
            Update Password
        </h2>

        <p class="text-center text-gray-500 mb-6">
            Silakan masukkan password baru Anda
        </p>

        <!-- Success -->
        @if(session('success'))
            <div class="bg-green-100 text-green-600 p-2 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-600 p-2 rounded mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('update.password') }}" method="POST">
            @csrf

            <!-- Hidden Email -->
            <input type="hidden" name="email" value="{{ session('email') }}">

            <!-- Password -->
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Password Baru</label>
                <input 
                    type="password" 
                    name="password" 
                    class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    placeholder="Masukkan password baru"
                    required
                >
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Konfirmasi Password</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    placeholder="Ulangi password"
                    required
                >
            </div>

            <!-- Button -->
            <button 
                type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-lg font-semibold transition"
            >
                Update Password
            </button>
        </form>

        <!-- Back -->
        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:underline">
                Kembali ke Login
            </a>
        </div>

    </div>

</body>
</html>