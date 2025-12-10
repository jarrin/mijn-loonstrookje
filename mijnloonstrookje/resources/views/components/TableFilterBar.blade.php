<div class="table-filter-bar">
    <div class="search-container">
        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <path d="m21 21-4.35-4.35"></path>
        </svg>
        <input type="text" placeholder="Zoeken" class="search-input">
    </div>

    @foreach($filters as $filter)
    <div class="dropdown-container">
        <button class="dropdown-button">
            {{ $filter['label'] }}
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </button>
        <div class="dropdown-menu">
            @foreach($filter['options'] as $option)
                <a href="#" class="dropdown-item">{{ $option }}</a>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
