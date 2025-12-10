// Table Filter Bar Functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.search-input');
    const table = document.querySelector('table');
    const tableFilterBar = document.querySelector('.table-filter-bar');
    
    // Store active filters
    const activeFilters = {};
    
    // Mapping voor filter values naar database values
    const filterMappings = {
        0: { // Type gebruiker
            'bedrijf': ['employer', 'bedrijf'],
            'werknemer': ['employee', 'werknemer'],
            'administratie bureau': ['administration_office', 'administratie bureau', 'administratiekantoor']
        }
    };
    
    if (!tableFilterBar) return;
    
    // Event delegation voor dropdown buttons
    tableFilterBar.addEventListener('click', function(e) {
        const dropdownButton = e.target.closest('.dropdown-button');
        if (!dropdownButton) return;
        
        e.preventDefault();
        
        const container = dropdownButton.closest('.dropdown-container');
        const menu = container.querySelector('.dropdown-menu');
        
        // Close other dropdowns
        tableFilterBar.querySelectorAll('.dropdown-button').forEach(btn => {
            if (btn !== dropdownButton) {
                btn.classList.remove('active');
                btn.closest('.dropdown-container').querySelector('.dropdown-menu').classList.remove('active');
            }
        });
        
        // Toggle current dropdown
        dropdownButton.classList.toggle('active');
        menu.classList.toggle('active');
    });
    
    // Event delegation voor dropdown items
    tableFilterBar.addEventListener('click', function(e) {
        const dropdownItem = e.target.closest('.dropdown-item');
        if (!dropdownItem) return;
        
        e.preventDefault();
        
        const container = dropdownItem.closest('.dropdown-container');
        const button = container.querySelector('.dropdown-button');
        const menu = container.querySelector('.dropdown-menu');
        const dropdownIndex = Array.from(tableFilterBar.querySelectorAll('.dropdown-container')).indexOf(container);
        const selectedValue = dropdownItem.textContent.trim();
        
        // Check if "Alles" was clicked
        if (dropdownItem.classList.contains('dropdown-item-all')) {
            // Remove filter for this dropdown
            delete activeFilters[dropdownIndex];
            // Reset button to original label
            const labels = ['Type gebruiker', 'Alle gebruikers', 'Alle gebruikers'];
            button.innerHTML = `${labels[dropdownIndex]}<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>`;
        } else {
            // Update button text
            button.innerHTML = `${selectedValue}<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>`;
            
            // Store filter
            activeFilters[dropdownIndex] = selectedValue;
        }
        
        // Close dropdown
        button.classList.remove('active');
        menu.classList.remove('active');
        
        // Apply filters
        applyFilters();
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.table-filter-bar')) {
            tableFilterBar.querySelectorAll('.dropdown-button').forEach(button => {
                button.classList.remove('active');
                button.closest('.dropdown-container').querySelector('.dropdown-menu').classList.remove('active');
            });
        }
    });
    
    // Search functionality
    if (searchInput && table) {
        searchInput.addEventListener('input', function() {
            applyFilters();
        });
    }
    
    // Apply all filters
    function applyFilters() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        const tableRows = table.querySelectorAll('tbody tr');
        
        tableRows.forEach(row => {
            let showRow = true;
            
            // Check search filter (first column - Gebruiker)
            if (searchTerm) {
                const firstCell = row.querySelector('td');
                if (firstCell) {
                    const cellText = firstCell.textContent.toLowerCase();
                    if (!cellText.includes(searchTerm)) {
                        showRow = false;
                    }
                }
            }
            
            // Check dropdown filters
            Object.keys(activeFilters).forEach(filterIndex => {
                if (showRow) {
                    const filterValue = activeFilters[filterIndex].toLowerCase();
                    const cells = row.querySelectorAll('td');
                    
                    // Dropdown 0 (Type gebruiker) -> column 3 (Type/Role)
                    if (filterIndex === '0') {
                        const typeCell = cells[3];
                        if (typeCell) {
                            const cellText = typeCell.textContent.toLowerCase();
                            // Check if cell matches any mapped values
                            const mappedValues = filterMappings[0][filterValue] || [];
                            const matches = mappedValues.some(val => cellText.includes(val));
                            if (!matches) {
                                showRow = false;
                            }
                        }
                    }
                    // Dropdown 1 (Status) -> column 4 (Status)
                    else if (filterIndex === '1') {
                        const statusCell = cells[4];
                        if (statusCell) {
                            const cellText = statusCell.textContent.toLowerCase();
                            if (!cellText.includes(filterValue)) {
                                showRow = false;
                            }
                        }
                    }
                    // Dropdown 2 is for sorting, not filtering
                }
            });
            
            row.style.display = showRow ? '' : 'none';
        });
    }
});
