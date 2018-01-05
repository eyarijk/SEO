@if($toast)
    <script>toastr["{{$toast['status']}}"]("{{$toast['message']}}")</script>
@endif