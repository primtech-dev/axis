@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.toast) {
                window.toast.success('{{ session('success') }}');
            }
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.toast) {
                window.toast.error('{{ session('error') }}');
            }
        });
    </script>
@endif

@if(session('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.toast) {
                window.toast.warning('{{ session('warning') }}');
            }
        });
    </script>
@endif

@if(session('info'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.toast) {
                window.toast.info('{{ session('info') }}');
            }
        });
    </script>
@endif
