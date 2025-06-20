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

    $(document).ready(function() {
        // Set dashboard as active by default
        $('a[data-page="dashboard.php"]').addClass('active');

        // Function to load content
        function loadContent(page) {
            $.ajax({
                url: page,
                method: 'GET',
                success: function(response) {
                    $('#main-content').html(response);
                    // Update active state
                    $('.nav-link').removeClass('active');
                    $(`a[data-page="${page}"]`).addClass('active');
                },
                error: function(xhr, status, error) {
                    console.error('Error loading content:', error);
                }
            });
        }

        // Handle menu item clicks
        $('.nav-link').click(function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            if (page) {
                loadContent(page);
            }
        });
    });

