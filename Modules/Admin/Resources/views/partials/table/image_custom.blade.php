<div class="thumbnail-holder">
    @if ($file)
        <img src="{{ $file }}" alt="thumbnail" onclick="window.open('{{ $file }}')" style="cursor: pointer;">
    @else
        <i class="fa fa-picture-o"></i>
    @endif
</div>
