(function ($) {
    
    //add Element Actions on elementor init
    $(window).on('elementor/frontend/init', function() {

        elementorFrontend.hooks.addAction( 'frontend/element_ready/form.default', function($scope){
            $scope.find('input, textarea').focus(function(e) {
                var name = $(this).attr('id');
                if ($(this).val()) {
                    $('label[for="' + name + '"]').addClass('active');
                }
                if ($('label[for="' + name + '"]').length > 0) {
                    $('label[for="' + name + '"]').addClass('active');
                }
            });

            $scope.find('input, textarea').blur(function(e) {
                var name = $(this).attr('id');
                if ($('label[for="' + name + '"]').length > 0) {
                    $('label[for="' + name + '"]').removeClass('active');
                }
                if ($(this).val()) {
                    $('label[for="' + name + '"]').addClass('active');
                }
            });
        });
    });
}(jQuery));
