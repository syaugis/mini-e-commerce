<script type="text/javascript">
    {{-- Success Message --}}
    @if (Session::has('success'))
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        Toast.fire({
            icon: 'success',
            title: '{{ Session::get('success') }}'
        });
    @endif
    @if (Session::has('successModal'))
        Swal.fire({
            icon: 'success',
            title: 'Done',
            text: '{{ Session::get('successModal') }}',
            confirmButtonColor: "#3a57e8"
        });
    @endif
    {{-- Errors Message --}}
    @if (Session::has('error'))
        Swal.fire({
            icon: 'error',
            title: 'Opps!!!',
            text: '{{ Session::get('error') }}',
            confirmButtonColor: "#3a57e8"
        });
    @endif
    @if (Session::has('errors') || (isset($errors) && is_object($errors) && $errors->any()))
        @php
            $errorMessages = is_string(Session::get('errors')) ? [Session::get('errors')] : $errors->all();
        @endphp

        @if (count($errorMessages) > 0)
            var errorMessage = @json($errorMessages[0]);

            Swal.fire({
                icon: 'error',
                title: 'Opps!!!',
                text: errorMessage,
                confirmButtonColor: "#3a57e8"
            });
        @endif
    @endif
</script>
