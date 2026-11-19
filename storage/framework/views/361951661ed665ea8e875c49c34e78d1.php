<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">
            Lupa Password
        </h2>

        <p class="text-center text-gray-500 mb-6">
            Masukkan email untuk menerima kode OTP
        </p>

        <!-- Success -->
        <?php if(session('success')): ?>
            <div class="bg-green-100 text-green-600 p-2 rounded mb-4 text-sm">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <!-- Error -->
        <?php if($errors->any()): ?>
            <div class="bg-red-100 text-red-600 p-2 rounded mb-4 text-sm">
                <?php echo e($errors->first()); ?>

            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('send.otp')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    placeholder="Masukkan email"
                    required
                >
            </div>

            <button 
                type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-lg font-semibold transition"
            >
                Kirim OTP
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="<?php echo e(route('login')); ?>" class="text-sm text-gray-500 hover:underline">
                Kembali ke Login
            </a>
        </div>

    </div>

</body>
</html><?php /**PATH C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\resources\views\auth\forgot-password.blade.php ENDPATH**/ ?>