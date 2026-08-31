{{-- SweetAlert2 for Logout Confirmation --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmLogout(event) {
        if (event) event.preventDefault();
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: 'Apakah Anda yakin ingin keluar dari sistem?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup: 'rounded-2xl shadow-2xl font-sans',
                    confirmButton: 'rounded-xl font-bold px-5 py-2.5 shadow-md',
                    cancelButton: 'rounded-xl font-semibold px-5 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.getElementById('logout-form');
                    if (!form) {
                        form = document.createElement('form');
                        form.id = 'logout-form';
                        form.method = 'POST';
                        form.action = '{{ route("logout") }}';
                        const csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = '{{ csrf_token() }}';
                        form.appendChild(csrf);
                        document.body.appendChild(form);
                    }
                    form.submit();
                }
            });
        } else {
            if (confirm('Apakah Anda yakin ingin keluar dari sistem?')) {
                let form = document.getElementById('logout-form');
                if (!form) {
                    form = document.createElement('form');
                    form.id = 'logout-form';
                    form.method = 'POST';
                    form.action = '{{ route("logout") }}';
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    form.appendChild(csrf);
                    document.body.appendChild(form);
                }
                form.submit();
            }
        }
    }
</script>
