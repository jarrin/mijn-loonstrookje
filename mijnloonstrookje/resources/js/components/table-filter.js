// Table Filter Bar Functionality
document.addEventListener('DOMContentLoaded', function() {
    const dropdownButtons = document.querySelectorAll('.dropdown-button');
    
    dropdownButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Close other dropdowns
            dropdownButtons.forEach(otherButton => {
                if (otherButton !== button) {
                    otherButton.classList.remove('active');
                }
            });
            
            // Toggle current dropdown
            this.classList.toggle('active');
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown-container')) {
            dropdownButtons.forEach(button => {
                button.classList.remove('active');
            });
        }
    });
});
