<p class="menu-label">
    Контекстная реклама
</p>
@foreach($contexts as $context)
    <form method="POST" action="/context/redirect" class="context">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{$context->id}}">
        <input type="submit" id="context-{{$context->id}}" style="display: none;">
    </form>
    <article class="message is-link">
        <div class="message-body">
            <div >
                <a onclick="getElementById('context-{{$context->id}}').click();" class="context" style="text-decoration: none;">{{$context->description}}<br>
                </a>
            </div>
    </article>
@endforeach
<hr class="time-hr">
<span class="p-l-10" style="font-size: 14px;"><i class="fa fa-clock-o" aria-hidden="true"></i> Время на сайте: {{date('H:m')}}</span>
<hr class="time-hr">

@section('ajax')
    <script>
        $(document).ready(function() {

            $('form.context').submit(function(event) {
                event.preventDefault();

                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(result) {
                        window.open(result);
                    },
                });

            });

        });
    </script>
@endsection