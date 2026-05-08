<div id="luminix-embed" style="display: none" aria-hidden="true">
    <div id="luminix-data::config" data-json="1" data-value="{{json_encode(Luminix\Frontend\Facades\Boot::get())}}"></div>
    @if (isset($catchables))
        @foreach($catchables as $error)
            @error($error)
                <div id="luminix-error::{{$error}}" data-value="{{$message}}"></div>
            @enderror
        @endforeach
    @endif
</div>
