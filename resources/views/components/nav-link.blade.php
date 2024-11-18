@props(['title' => '', 'link' => '', 'active' => false])

<li class="nav-item w-100">
    <button class="btn btn{{$active ? '' : '-outline' }}-secondary w-100">
        <a class="nav-link text-center {{$active ? 'text-white' : '' }}" href="{{ $link }}">
            {{ $title }}
        </a>
    </button>
</li>
