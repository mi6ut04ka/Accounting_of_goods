@props(['title' => '', 'link' => '', 'active' => false])

<li {{$attributes}} class="nav-item">
    <a href="{{ $link }}"
       class="btn btn{{$active ? '' : '-outline' }}-secondary text-center w-100 d-block py-2">
        {{ $title }}
    </a>
</li>
