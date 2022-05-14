
jQuery.noConflict();
jQuery(document).ready(function($) {
    
    
    console.log( window.cookie );
    // var params = new URLSearchParams( window.location.href );
    // params.append('name', 'DUY');

    function set_page( element ) {
        return parseInt( element.html() );
    }

    $(document).on('click', '.pagination a.page-link', function(event) {
        event.preventDefault();
        var page = set_page( $(this).clone() );
        var per_page = $(this).attr('per-page');
        var query_vars = kdi_ajax.query_vars;

        $.ajax({
            type: 'GET',
            url: kdi_ajax.ajax_url,
            data: {
                action: 'kdi_page_product_cat',
                // query_vars: kdi_ajax.query_vars,
                query_vars,
                page,
                per_page,
            },
            beforeSend: function() {
                var loading = `
                <div class="d-flex justify-content-center w-100">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                `;

                $('ul.products').html( loading );
            },
            success: function(data, textStatus, jqXHR) {
                // alert("SUCCESS");
                var { content, pagination, per_page } = data;
                if( content) {
                    $('ul.products').html( content );
                }
                if( pagination ) {
                    $('nav.product--pagination').html( pagination );
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                var error = `<div class="mx-auto">Something error! please check your network. Or comeback later!</div>`;
                $('ul.products').html( error );
                console.log(JSON.stringify(textStatus));
                console.log(JSON.stringify(errorThrown));
                event.target.disabled = false;
            },
        });
    });
});