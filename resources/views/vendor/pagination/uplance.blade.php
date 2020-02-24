
@if ($paginator->hasPages())
<div class="pagination-container margin-top-60 margin-bottom-60">
    <nav class="pagination">
        <ul>
            @if (!$paginator->onFirstPage())
            <li class="pagination-arrow">
                <a href="{{ $paginator->previousPageUrl() }}" aria-label="Previous" class="ripple-effect">
                    <i class="icon-material-outline-keyboard-arrow-left"></i>
                </a>
            </li>
            @endif
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li><a style="color: #fff !important;" class="ripple-effect">{{ $element }}</a></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><a style="color: #fff !important;" class="ripple-effect current-page">{{ $page }}</a></li>
                        @else
                            <li><a href="{{ $url }}" class="ripple-effect">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach
            @if ($paginator->hasMorePages())
            <li class="pagination-arrow">
                <a href="{{ $paginator->nextPageUrl() }}" aria-label="Previous" class="ripple-effect">
                    <i class="icon-material-outline-keyboard-arrow-right"></i>
                </a>
            </li>
            @endif
        </ul>
    </nav>
</div>
@endif
