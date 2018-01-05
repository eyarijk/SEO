@if(session('toast'))
    <script>toastr["{{ session('toaststatus') }}"]("{{ session('toast') }}")</script>
@endif