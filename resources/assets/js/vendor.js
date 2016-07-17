$(function(){
    $("input.items_quantity").change(function(){
        var form = $(this).closest("form");
        form.submit();
    });

    $("input.shipping_method").click(function(){
        var form = $(this).closest("form");
        form.submit();
    });

    $("a .fa-times-circle").click(function(){
        var form = $(this).closest("form");
        var qty = form.find("input.items_quantity:first");
        qty.val(0);
        form.submit();
    });

    $('a.view-details').on('click', function(e) {
        e.preventDefault();
        var $this = $(this);
        var $collapse = $this.closest('.collapse-group').find('.collapse');
        var $hide = $this.closest('form').find('.hide-details');
        $hide.show();
        $this.toggle();
        $collapse.collapse('toggle');
    });

    $('a.hide-details').on('click', function(e) {
        e.preventDefault();
        var $this = $(this);
        var $collapse = $this.closest('.collapse-group').find('.collapse');
        var $show = $this.closest('form').find('.view-details');
        $show.show();
        $this.toggle();
        $collapse.collapse('toggle');
    });

    //Handles menu drop down
    $('a.icon.dropdown-toggle').on('click', function (event) {
        $(this).parent().toggleClass('open');
    });

    $('body').on('click', function (e) {
        if (!$('a.icon.dropdown-toggle').is(e.target)
            && $('a.icon.dropdown-toggle').has(e.target).length === 0
            && $('.open').has(e.target).length === 0
        ) {
            $('a.icon.dropdown-toggle').removeClass('open');
        }
    });
});

