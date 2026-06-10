jQuery(document).ready(function($) {
    var mediaQuery = window.matchMedia('(max-width: 768px)');
    function handleMobileViewForTab(mediaQuery) {
        var element = $('.pxlt_tab');
        if (element.length) {
            if (mediaQuery.matches) {
                element.removeClass('active');
                handleMobileViewForTabContent(element);
            } else {

            }
        }
    }
    
    function handleMobileViewForTabContent(element){
        element.on('click', function() {
            $(this).toggleClass('active'); 
            $('#pxlt_tab_content').toggle(); 
        });
    }
    handleMobileViewForTab(mediaQuery);
    mediaQuery.addEventListener('change', handleMobileViewForTab);
});
