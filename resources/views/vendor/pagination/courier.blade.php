<div class="card-inner">
    <div class="nk-block-between-md g-3">
        <nav class="g">
          @if ($paginator->hasPages())
              <ul class="pagination pagination-primary justify-content-center">
   
                @if ($paginator->onFirstPage())
                    <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">← Previous</a></li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="← Previous">← Previous</a>
                    </li>
                @endif


  
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li class="page-item"><span>{{ $element }}</span></li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item my-active"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach


    
                @if ($paginator->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next →</a></li>
                @else
                    <li class="page-item"><span class="page-link">Next →</span></li>
                @endif
             </ul>
          @endif 
            </nav>
    </div>
</div>
<style>
    .page-link {
        display: block;
        padding: 0.5rem 0.75rem;
        margin-left: -1px;
        line-height: 1.25;
    
    }
    .my-active > .page-link {
        background-color: #222d6f;
        color: white;
        font-weight: bold; 
        font-size: 1rem;
        scale: 1.1;
        z-index: 1;
    }
</style>