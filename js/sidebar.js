document.addEventListener('DOMContentLoaded', function() {
    const memberOption = document.querySelector('.member-option');
    const arrow = document.querySelector('.toggle-arrow');

    // Toggle arrow rotation only
    memberOption.addEventListener('click', function(e) {
        e.stopPropagation();
        arrow.classList.toggle('active');
    });
});
