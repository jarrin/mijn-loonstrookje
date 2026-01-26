@if ($paginator->hasPages())
    <nav class="custom-pagination">
        <div class="pagination-info">
            Toont {{ $paginator->firstItem() }} tot {{ $paginator->lastItem() }} van {{ $paginator->total() }} resultaten
        </div>
        
        <ul class="pagination-list">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="pagination-item disabled">
                    <span class="pagination-link">← Vorige</span>
                </li>
            @else
                <li class="pagination-item">
                    <a href="{{ $paginator->previousPageUrl() }}" class="pagination-link" rel="prev">← Vorige</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="pagination-item disabled"><span class="pagination-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="pagination-item active">
                                <span class="pagination-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="pagination-item">
                                <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="pagination-item">
                    <a href="{{ $paginator->nextPageUrl() }}" class="pagination-link" rel="next">Volgende →</a>
                </li>
            @else
                <li class="pagination-item disabled">
                    <span class="pagination-link">Volgende →</span>
                </li>
            @endif
        </ul>
    </nav>

    <style>
        .custom-pagination {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            margin-top: 1.5rem;
            padding: 1rem 0;
        }

        .pagination-info {
            color: #6B7280;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .pagination-list {
            display: flex;
            gap: 0.5rem;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pagination-item {
            display: inline-block;
        }

        .pagination-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 2.5rem;
            height: 2.5rem;
            padding: 0 0.75rem;
            border: 1px solid #E5E7EB;
            border-radius: 0.5rem;
            background-color: white;
            color: #374151;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .pagination-item:not(.disabled):not(.active) .pagination-link:hover {
            background-color: rgba(59, 130, 246, 0.1);
            border-color: #3B82F6;
            color: #3B82F6;
            transform: translateY(-1px);
        }

        .pagination-item.active .pagination-link {
            background-color: #3B82F6;
            border-color: #3B82F6;
            color: white;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .pagination-item.disabled .pagination-link {
            background-color: #F9FAFB;
            color: #9CA3AF;
            cursor: not-allowed;
            border-color: #E5E7EB;
        }

        @media (max-width: 640px) {
            .pagination-info {
                font-size: 0.75rem;
            }

            .pagination-link {
                min-width: 2rem;
                height: 2rem;
                padding: 0 0.5rem;
                font-size: 0.75rem;
            }
        }
    </style>
@endif
