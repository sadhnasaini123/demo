document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.querySelector('.toggle-button');
    
    toggleButton.addEventListener('click', function() {
        this.classList.toggle('active');
        // Add your sidebar toggle logic here
    });
});
