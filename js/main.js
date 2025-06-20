// Remove or comment out any existing toggle event listeners
// const toggleButton = document.querySelector('.navbar-toggler');
// toggleButton.addEventListener('click', function() {
//     // Remove toggle functionality
// });

// Remove or comment out toggle animation code
document.querySelector('.navbar-toggler').addEventListener('click', function() {
    // Remove the class that triggers the cross icon animation
    this.classList.remove('active');
});