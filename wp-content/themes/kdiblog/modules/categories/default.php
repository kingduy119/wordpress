<?php 
    extract( $args );
?>

<div class="products-filter card mb-2">
    <div class="card-header">
        Filter by category
    </div>
    <div class="card-body">
        <?php foreach( $categories as $cat ) : ?>
            <div class="form-check">
                <input
                    id="product-content"
                    class="form-check-input"
                    value="<?php echo esc_attr( $cat->slug ); ?>"
                    type="checkbox"
                >
                <label class="form-check-label" for="product-content"><?php echo esc_html( $cat->name ) . '('. $cat->count .')'; ?></label>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="products-filter card mb-2">
    <div class="card-header">
        Filter by price
    </div>
    <div class="card-body">
        <div class="input-group mb-3">
            <span class="input-group-text">min</span>
            <input value="0" type="number" step="1000" min="0" id="min-price" type="text" class="form-control" aria-label="Amount (min)">
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text">max</span>
            <input value="500000" type="number" step="1000" max="1000000000" id="max-price" type="text" class="form-control" aria-label="Amount (max)">
        </div>
    </div>
</div>

<div class="products-filter card mb-2">
    <div class="card-header">
        Sort by total
    </div>
    <div class="card-body">
        <div class="input-group mb-3">
            <select class="form-select" id="filter-orderby">
                <option value="rand">Random</option>
                <option value="price">Price</option>
                <option value="sales">Sales</option>
                <option value="name">Name</option>
            </select>
        </div>
        <div class="input-group mb-3">
            <select class="form-select" id="filter-order">
                <option value="DESC">DESC</option>
                <option value="ASC">ASC</option>
            </select>
        </div>
    </div>
</div>

<button 
    type="button"
    id="btn-test"
    class="btn btn-info text-light mt-2"
    onclick="on_filter_by_category(event);"
>Filter</button>

<!--  -->
<script>

    function on_filter_by_category(event) {
        event.target.disabled = true;

        jQuery('ul.products').html(`
        <div class="d-flex justify-content-center w-100">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        `);

        // Filter by category
        var checked = "";
        var checklist = jQuery(".products-filter input:checkbox:checked");
        checklist.map(function(index, checkbox) {
            checked += checkbox.value + ',';
        });

        // Filter by price
        var min_price = jQuery('input#min-price').val();
        var max_price = jQuery('input#max-price').val();

        // Sort
        var orderby = jQuery('select#filter-orderby').val();
        var order   = jQuery('select#filter-order').val();
        console.log(`Order: ${order}`);

        var wp_ajax_url = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
        jQuery.ajax({
            url: wp_ajax_url,
            type: 'GET',
            data: {
                action: 'kdi_query_product',
                post_in: checked,
                min_price,
                max_price,
                orderby,
                order,
            },
            success: function(data, textStatus, jqXHR) {
                jQuery('ul.products').html(data);
                event.target.disabled = false;
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("ERROR");
                console.log(JSON.stringify(textStatus));
                console.log(JSON.stringify(errorThrown));
                event.target.disabled = false;
            }
        });

        return false;
    }
</script>