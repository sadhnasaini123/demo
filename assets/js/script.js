
    function toggleMemberSubMenu() {
        const submenu = document.getElementById('member-submenu');
        submenu.style.display = submenu.style.display === 'none' ? 'block' : 'none';
    }

    function handleMemberOption(option) {
        let message = '';
        if (option === 'search') message = 'Search Members UI loaded.';
        else if (option === 'active') message = 'Showing Active Members.';
        else if (option === 'inactive') message = 'Showing Inactive Members.';
        
        document.getElementById('member-content').innerHTML = `<h3>${message}</h3>`;
    }

