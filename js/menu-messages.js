document.addEventListener('DOMContentLoaded', function() {
    const menuItems = document.querySelectorAll('.submenu .menu-item a');
    const dashboard = document.getElementById('dashboard-content');

    menuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const message = getMemberMessage(this.textContent.trim());
            showDashboardMessage(message);
        });
    });

    function getMemberMessage(menuType) {
        switch(menuType) {
            case 'Search':
                return 'Search members section loaded';
            case 'Active':
                return 'Showing active members';
            case 'In-active':
                return 'Showing inactive members';
            default:
                return 'Selected members section';
        }
    }

    function showDashboardMessage(message) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'dashboard-message';
        messageDiv.textContent = message;
        
        // Clear previous messages
        const oldMessages = dashboard.querySelectorAll('.dashboard-message');
        oldMessages.forEach(msg => msg.remove());
        
        dashboard.prepend(messageDiv);
    }
});
