
jQuery.noConflict();
jQuery(document).ready(function($) {

    $('#page-sidebar p, #page-footer p').each(function() {
        if( $(this).text() == '' || $(this).text() == '\t\t' ) {
            $(this).remove();
        }
    });
    

    var loading = $('div#products-loading');
    var product_pagination = $('nav.product--pagination');
    var product_list = $('ul.products');

    var filters = { 
        per_page: product_list.attr('total'),
        min_price: 0,
    };

    function showLoading() { if( loading ) loading.show(); }
    function hideLoading() { if( loading ) loading.hide(); }

    function filterProducts() {
        $.ajax({
            type: 'GET',
            timeout: 5000,
            url: kdi_ajax.ajax_url,
            data: {
                ...filters,
                query_vars: kdi_ajax.query_vars, //kdi_ajax.query_vars see more on modules/kdi-post.php
                action: 'kdi_page_product_cat',
            },
            beforeSend: function() {
                showLoading();
                product_pagination.html('');
            },
            success: function(data, textStatus, jqXHR) {
                var { content, pagination } = data;
                if( content) {
                    product_list.html( content );
                }
                else {
                    product_list.html( "ELSE" );
                }

                if( pagination ) {
                    product_pagination.html( pagination );
                    $('.pagination a.page-link').click( function(event) {
                        event.preventDefault();
                        filters = {...filters, paged: $(this).attr('paged') };
                        filterProducts();
                    });
                }

                hideLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                var error = `<div class="mx-auto">Something error! please check your network. Or comeback later!</div>`;
                product_list.html( error );

                hideLoading();
            },
        })
    }

    function onSelectChange(event) {
        var name = $(this).attr('name');
        var value = $(this).val();
        filters = {...filters, [name]: value, paged: 1 };
        
        filterProducts();
    }

    // Filter by category:
    $('#products-filter #filter-product-cat').click( function(event) {
        var temp = '';
        var name = $(this).attr('name');
        $('#products-filter #filter-product-cat:checked').each(function() {
            temp += $(this).val() + ',';
        });
        filters = {...filters, [name]: temp, paged: 1 };

        filterProducts();
    } );
    $('#products-filter select#filter-orderby').change( onSelectChange );
    $('#products-filter select#filter-order').change( onSelectChange );
    $('#products-filter #min-price').change( onSelectChange );
    $('#products-filter #max-price').change( onSelectChange );

    $('.pagination a.page-link').click( function(event) {
        event.preventDefault();
        filters = {...filters, paged: $(this).attr('paged') };
        filterProducts();
    });

    hideLoading();
});