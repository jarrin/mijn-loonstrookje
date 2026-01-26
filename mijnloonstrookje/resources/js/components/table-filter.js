// Table Filter Bar Functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.search-input');
    const table = document.querySelector('table');
    const tableFilterBar = document.querySelector('.table-filter-bar');
    
    // Store active filters
    const activeFilters = {};
    
    // Detect which page we're on based on table id or page title
    const pageType = detectPageType();
    
    // Get original filter labels for reset functionality
    const originalLabels = [];
    if (tableFilterBar) {
        tableFilterBar.querySelectorAll('.dropdown-button').forEach(button => {
            originalLabels.push(button.textContent.trim());
        });
    }
    
    // Mapping voor filter values naar database values per pagina type
    const filterMappings = {
        dashboard: {
            0: { // Type gebruiker
                'bedrijf': ['employer', 'bedrijf'],
                'werknemer': ['employee', 'werknemer'],
                'administratie bureau': ['administration_office', 'administratie bureau', 'administratiekantoor']
            }
        },
        logs: {
            0: { // Type actie
                'login': ['login'],
                'document uploaded': ['document_uploaded', 'document uploaded'],
                'document revised': ['document_revised', 'document revised'],
                'document deleted': ['document_deleted', 'document deleted'],
                'document restored': ['document_restored', 'document restored'],
                'employee created': ['employee_created', 'employee created'],
                'admin office added': ['admin_office_added', 'admin office added']
            }
        },
        facturation: {
            0: { // Status
                'betaald': ['paid', 'betaald'],
                'open': ['open', 'pending'],
                'vervallen': ['overdue', 'vervallen'],
                'geannuleerd': ['cancelled', 'canceled', 'geannuleerd']
            }
        },
        'employer-employees': {},
        'employer-documents': {
            0: { // Type document
                'loonstrook': ['payslip', 'loonstrook'],
                'jaaroverzicht': ['annual_statement', 'annual statement', 'jaaroverzicht'],
                'overig': ['other', 'overig']
            }
        },
        'employer-activity': {
            0: { // Type actie (same as logs)
                'login': ['login'],
                'document uploaded': ['document_uploaded', 'document uploaded'],
                'document revised': ['document_revised', 'document revised'],
                'document deleted': ['document_deleted', 'document deleted'],
                'document restored': ['document_restored', 'document restored'],
                'employee created': ['employee_created', 'employee created']
            }
        },
        'employee-dashboard': {
            0: { // Type document
                'loonstrook': ['payslip', 'loonstrook'],
                'jaaroverzicht': ['annual_statement', 'annual statement', 'jaaroverzicht'],
                'overig': ['other', 'overig']
            }
        },
        'admin-office-employees': {}
    };
    
    function detectPageType() {
        // Super Admin pages
        if (document.querySelector('#superadmin-table')) return 'dashboard';
        if (document.querySelector('.superadmin-page-title')?.textContent.includes('Systeem Logs')) return 'logs';
        if (document.querySelector('#super-admin-facturation')) return 'facturation';
        
        // Employer pages
        if (document.querySelector('.employer-page-title')?.textContent.includes('Medewerkers Lijst')) return 'employer-employees';
        if (document.querySelector('.employer-page-title')?.textContent.includes('Documenten van')) return 'employer-documents';
        if (document.querySelector('.employer-activity-section')) return 'employer-activity';
        
        // Employee pages
        if (document.querySelector('.employee-page-title')?.textContent.includes('Mijn Documenten')) return 'employee-dashboard';
        
        // Admin Office pages
        if (document.querySelector('.employees-title')?.textContent.includes('Medewerkers -')) return 'admin-office-employees';
        
        return 'dashboard';
    }
    
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
            const originalLabel = originalLabels[dropdownIndex];
            button.innerHTML = `${originalLabel}<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>`;
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
        
        // Filter rows
        let visibleRows = Array.from(tableRows).filter(row => {
            // Skip empty rows
            if (row.textContent.includes('Geen')) return true;
            
            let showRow = true;
            const cells = row.querySelectorAll('td');
            
            // Check search filter - search across all columns or specific columns based on page
            if (searchTerm) {
                const searchColumns = getSearchColumns(pageType);
                const matchFound = searchColumns.some(colIndex => {
                    if (cells[colIndex]) {
                        return cells[colIndex].textContent.toLowerCase().includes(searchTerm);
                    }
                    return false;
                });
                if (!matchFound) showRow = false;
            }
            
            // Check dropdown filters
            Object.keys(activeFilters).forEach(filterIndex => {
                if (showRow) {
                    const filterValue = activeFilters[filterIndex].toLowerCase();
                    
                    // Apply page-specific filtering
                    if (pageType === 'dashboard') {
                        showRow = applyDashboardFilter(filterIndex, filterValue, cells);
                    } else if (pageType === 'logs') {
                        showRow = applyLogsFilter(filterIndex, filterValue, cells, row);
                    } else if (pageType === 'facturation') {
                        showRow = applyFacturationFilter(filterIndex, filterValue, cells, row);
                    } else if (pageType === 'employer-employees') {
                        showRow = applyEmployerEmployeesFilter(filterIndex, filterValue, cells);
                    } else if (pageType === 'employer-documents') {
                        showRow = applyEmployerDocumentsFilter(filterIndex, filterValue, cells, row);
                    } else if (pageType === 'employer-activity') {
                        showRow = applyEmployerActivityFilter(filterIndex, filterValue, cells, row);
                    } else if (pageType === 'employee-dashboard') {
                        showRow = applyEmployeeDashboardFilter(filterIndex, filterValue, cells, row);
                    } else if (pageType === 'admin-office-employees') {
                        showRow = applyAdminOfficeEmployeesFilter(filterIndex, filterValue, cells);
                    }
                }
            });
            
            return showRow;
        });
        
        // Show/hide rows
        tableRows.forEach(row => {
            row.style.display = visibleRows.includes(row) ? '' : 'none';
        });
        
        // Apply sorting if applicable
        applySorting(visibleRows);
    }
    
    function getSearchColumns(pageType) {
        // Return array of column indices to search in
        switch(pageType) {
            case 'dashboard': return [0, 1, 2]; // Gebruiker, Bedrijf, Email
            case 'logs': return [1, 2, 4]; // Gebruiker, Bedrijf, Beschrijving
            case 'facturation': return [0]; // Bedrijf
            case 'employer-employees': return [0, 1]; // Naam, Email
            case 'employer-documents': return [0, 1]; // Document Naam (and Medewerker if present)
            case 'employer-activity': return [1, 3]; // Gebruiker, Beschrijving
            case 'employee-dashboard': return [0, 1]; // Document Naam, Type
            case 'admin-office-employees': return [0, 1]; // Naam, Email
            default: return [0];
        }
    }
    
    function applyDashboardFilter(filterIndex, filterValue, cells) {
        // Dropdown 0 (Type gebruiker) -> column 3 (Type/Role)
        if (filterIndex === '0' && cells[3]) {
            const cellText = cells[3].textContent.toLowerCase();
            const mappedValues = filterMappings.dashboard[0][filterValue] || [];
            return mappedValues.some(val => cellText.includes(val));
        }
        // Dropdown 1 (Status) -> column 4 (Status)
        else if (filterIndex === '1' && cells[4]) {
            const cellText = cells[4].textContent.toLowerCase();
            return cellText.includes(filterValue);
        }
        // Dropdown 2 is for sorting
        return true;
    }
    
    function applyLogsFilter(filterIndex, filterValue, cells, row) {
        // Dropdown 0 (Type actie) -> column 3 (Actie)
        if (filterIndex === '0' && cells[3]) {
            const cellText = cells[3].textContent.toLowerCase();
            const mappedValues = filterMappings.logs[0][filterValue] || [];
            return mappedValues.some(val => cellText.includes(val));
        }
        // Dropdown 1 (Periode) - filter based on timestamp
        else if (filterIndex === '1' && cells[0]) {
            return filterByPeriod(filterValue, cells[0].textContent);
        }
        // Dropdown 2 is for sorting
        return true;
    }
    
    function applyFacturationFilter(filterIndex, filterValue, cells, row) {
        // Dropdown 0 (Status) -> column 3 (Status)
        if (filterIndex === '0' && cells[3]) {
            const cellText = cells[3].textContent.toLowerCase();
            const mappedValues = filterMappings.facturation[0][filterValue] || [];
            return mappedValues.some(val => cellText.includes(val));
        }
        // Dropdown 1 (Periode) - filter based on date
        else if (filterIndex === '1' && cells[1]) {
            return filterByPeriod(filterValue, cells[1].textContent);
        }
        // Dropdown 2 is for sorting
        return true;
    }
    
    function applyEmployerEmployeesFilter(filterIndex, filterValue, cells) {
        // Dropdown 0 (Status) -> column 2 (Status)
        if (filterIndex === '0' && cells[2]) {
            const cellText = cells[2].textContent.toLowerCase();
            return cellText.includes(filterValue.toLowerCase());
        }
        // Dropdown 1 is for sorting
        return true;
    }
    
    function applyEmployerDocumentsFilter(filterIndex, filterValue, cells, row) {
        // Check if employee column exists (cells[0] would be employee in all-documents view)
        const hasEmployeeColumn = !document.querySelector('.employer-page-title')?.textContent.match(/Documenten van [A-Z]/);
        const typeColumnIndex = hasEmployeeColumn ? 2 : 1;
        const dateColumnIndex = hasEmployeeColumn ? 6 : 5;
        
        // Dropdown 0 (Type document)
        if (filterIndex === '0' && cells[typeColumnIndex]) {
            const cellText = cells[typeColumnIndex].textContent.toLowerCase();
            const mappedValues = filterMappings['employer-documents'][0][filterValue] || [];
            return mappedValues.some(val => cellText.includes(val));
        }
        // Dropdown 1 (Periode) - filter based on upload date
        else if (filterIndex === '1' && cells[dateColumnIndex]) {
            return filterByPeriod(filterValue, cells[dateColumnIndex].textContent);
        }
        // Dropdown 2 is for sorting
        return true;
    }
    
    function applyEmployerActivityFilter(filterIndex, filterValue, cells, row) {
        // Dropdown 0 (Type actie) -> column 2 (Actie)
        if (filterIndex === '0' && cells[2]) {
            const badgeElement = cells[2].querySelector('.employer-activity-badge');
            if (badgeElement) {
                const cellText = badgeElement.textContent.toLowerCase();
                const mappedValues = filterMappings['employer-activity'][0][filterValue] || [];
                return mappedValues.some(val => cellText.includes(val));
            }
        }
        // Dropdown 1 (Periode) - filter based on timestamp
        else if (filterIndex === '1' && cells[0]) {
            return filterByPeriod(filterValue, cells[0].textContent);
        }
        // Dropdown 2 is for sorting
        return true;
    }
    
    function applyEmployeeDashboardFilter(filterIndex, filterValue, cells, row) {
        // Dropdown 0 (Type document) -> column 1 (Type)
        if (filterIndex === '0' && cells[1]) {
            const cellText = cells[1].textContent.toLowerCase();
            const mappedValues = filterMappings['employee-dashboard'][0][filterValue] || [];
            return mappedValues.some(val => cellText.includes(val));
        }
        // Dropdown 1 (Periode) - filter based on upload date (column 5)
        else if (filterIndex === '1' && cells[5]) {
            return filterByPeriod(filterValue, cells[5].textContent);
        }
        // Dropdown 2 is for sorting
        return true;
    }
    
    function applyAdminOfficeEmployeesFilter(filterIndex, filterValue, cells) {
        // Dropdown 0 (Status) -> column 2 (Status)
        if (filterIndex === '0' && cells[2]) {
            const cellText = cells[2].textContent.toLowerCase();
            return cellText.includes(filterValue.toLowerCase());
        }
        // Dropdown 1 is for sorting
        return true;
    }
    
    function filterByPeriod(period, dateText) {
        try {
            // Parse date from format "dd-mm-yyyy" or "dd-mm-yyyy hh:mm:ss"
            const dateParts = dateText.split(' ')[0].split('-');
            if (dateParts.length !== 3) return true;
            
            const itemDate = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            switch(period.toLowerCase()) {
                case 'vandaag':
                    return itemDate.toDateString() === today.toDateString();
                
                case 'deze week':
                    const weekAgo = new Date(today);
                    weekAgo.setDate(weekAgo.getDate() - 7);
                    return itemDate >= weekAgo;
                
                case 'deze maand':
                case 'vorige maand':
                    const isPrevious = period.toLowerCase() === 'vorige maand';
                    const targetMonth = isPrevious ? today.getMonth() - 1 : today.getMonth();
                    const targetYear = isPrevious && targetMonth < 0 ? today.getFullYear() - 1 : today.getFullYear();
                    return itemDate.getMonth() === (targetMonth + 12) % 12 && 
                           itemDate.getFullYear() === targetYear;
                
                case 'dit kwartaal':
                    const quarter = Math.floor(today.getMonth() / 3);
                    const itemQuarter = Math.floor(itemDate.getMonth() / 3);
                    return quarter === itemQuarter && itemDate.getFullYear() === today.getFullYear();
                
                case 'dit jaar':
                    return itemDate.getFullYear() === today.getFullYear();
                
                default:
                    return true;
            }
        } catch (e) {
            return true;
        }
    }
    
    function applySorting(rows) {
        // Check if sorting is selected (usually filter index 2)
        const sortValue = activeFilters['2'];
        if (!sortValue) return;
        
        const sortValueLower = sortValue.toLowerCase();
        const tbody = table.querySelector('tbody');
        
        // Convert NodeList to Array for sorting
        const rowsArray = Array.from(rows);
        
        rowsArray.sort((a, b) => {
            const aCells = a.querySelectorAll('td');
            const bCells = b.querySelectorAll('td');
            
            if (pageType === 'dashboard') {
                if (sortValueLower.includes('alfabetisch')) {
                    return aCells[0].textContent.localeCompare(bCells[0].textContent);
                } else if (sortValueLower.includes('datum')) {
                    // Assuming status or other date-related sorting
                    return 0; // Could be enhanced with actual date comparison
                }
            } else if (pageType === 'logs') {
                if (sortValueLower.includes('nieuwste')) {
                    return bCells[0].textContent.localeCompare(aCells[0].textContent);
                } else if (sortValueLower.includes('oudste')) {
                    return aCells[0].textContent.localeCompare(bCells[0].textContent);
                } else if (sortValueLower.includes('gebruiker')) {
                    return aCells[1].textContent.localeCompare(bCells[1].textContent);
                } else if (sortValueLower.includes('bedrijf')) {
                    return aCells[2].textContent.localeCompare(bCells[2].textContent);
                }
            } else if (pageType === 'facturation') {
                if (sortValueLower.includes('nieuwste')) {
                    return bCells[1].textContent.localeCompare(aCells[1].textContent);
                } else if (sortValueLower.includes('oudste')) {
                    return aCells[1].textContent.localeCompare(bCells[1].textContent);
                } else if (sortValueLower.includes('bedrag oplopend')) {
                    const aAmount = parseFloat(aCells[4].textContent.replace('€', '').replace(',', '.'));
                    const bAmount = parseFloat(bCells[4].textContent.replace('€', '').replace(',', '.'));
                    return aAmount - bAmount;
                } else if (sortValueLower.includes('bedrag aflopend')) {
                    const aAmount = parseFloat(aCells[4].textContent.replace('€', '').replace(',', '.'));
                    const bAmount = parseFloat(bCells[4].textContent.replace('€', '').replace(',', '.'));
                    return bAmount - aAmount;
                }
            } else if (pageType === 'employer-employees') {
                if (sortValueLower.includes('naam a-z')) {
                    return aCells[0].textContent.localeCompare(bCells[0].textContent);
                } else if (sortValueLower.includes('naam z-a')) {
                    return bCells[0].textContent.localeCompare(aCells[0].textContent);
                } else if (sortValueLower.includes('nieuwste')) {
                    // Could be enhanced with actual date comparison if available
                    return 0;
                } else if (sortValueLower.includes('oudste')) {
                    return 0;
                }
            } else if (pageType === 'employer-documents') {
                const hasEmployeeColumn = !document.querySelector('.employer-page-title')?.textContent.match(/Documenten van [A-Z]/);
                const nameColumnIndex = hasEmployeeColumn ? 1 : 0;
                const dateColumnIndex = hasEmployeeColumn ? 6 : 5;
                
                if (sortValueLower.includes('nieuwste')) {
                    return bCells[dateColumnIndex].textContent.localeCompare(aCells[dateColumnIndex].textContent);
                } else if (sortValueLower.includes('oudste')) {
                    return aCells[dateColumnIndex].textContent.localeCompare(bCells[dateColumnIndex].textContent);
                } else if (sortValueLower.includes('naam a-z')) {
                    return aCells[nameColumnIndex].textContent.localeCompare(bCells[nameColumnIndex].textContent);
                } else if (sortValueLower.includes('naam z-a')) {
                    return bCells[nameColumnIndex].textContent.localeCompare(aCells[nameColumnIndex].textContent);
                }
            } else if (pageType === 'employer-activity') {
                if (sortValueLower.includes('nieuwste')) {
                    return bCells[0].textContent.localeCompare(aCells[0].textContent);
                } else if (sortValueLower.includes('oudste')) {
                    return aCells[0].textContent.localeCompare(bCells[0].textContent);
                } else if (sortValueLower.includes('gebruiker')) {
                    return aCells[1].textContent.localeCompare(bCells[1].textContent);
                }
            } else if (pageType === 'employee-dashboard') {
                if (sortValueLower.includes('nieuwste')) {
                    return bCells[5].textContent.localeCompare(aCells[5].textContent);
                } else if (sortValueLower.includes('oudste')) {
                    return aCells[5].textContent.localeCompare(bCells[5].textContent);
                } else if (sortValueLower.includes('naam a-z')) {
                    return aCells[0].textContent.localeCompare(bCells[0].textContent);
                } else if (sortValueLower.includes('naam z-a')) {
                    return bCells[0].textContent.localeCompare(aCells[0].textContent);
                }
            } else if (pageType === 'admin-office-employees') {
                if (sortValueLower.includes('naam a-z')) {
                    return aCells[0].textContent.localeCompare(bCells[0].textContent);
                } else if (sortValueLower.includes('naam z-a')) {
                    return bCells[0].textContent.localeCompare(aCells[0].textContent);
                } else if (sortValueLower.includes('nieuwste')) {
                    // Could be enhanced with actual date comparison if available
                    return 0;
                } else if (sortValueLower.includes('oudste')) {
                    return 0;
                }
            }
            return 0;
        });
        
        // Re-append sorted rows
        rowsArray.forEach(row => tbody.appendChild(row));
    }
});
