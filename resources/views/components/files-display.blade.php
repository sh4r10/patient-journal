@if($files && count($files) > 0)
    <div class="flex flex-wrap gap-2">
        @foreach($files as $file)
            @if(strpos($file->mime, 'video') !== false)
                <div class="relative w-24 h-24"> 
                    <video class="absolute inset-0 w-full h-full object-cover rounded">
                        <source src="{{ asset($file->path) }}" type="{{ $file->mime }}">
                        Your browser does not support the video tag.
                    </video>
                </div>
            @else
                <img src="{{ asset($file->path) }}" alt="File Image" class="w-24 h-24 object-cover rounded"> 
            @endif
        @endforeach
    </div>
@endif
