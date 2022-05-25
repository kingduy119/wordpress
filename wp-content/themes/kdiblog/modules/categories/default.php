<?php 
    extract( $args );
?>

<div id="products-filter" class="card mb-2">
    <div class="card-header">
        Filter by category
    </div>
    <div class="card-body">
        <?php foreach( $categories as $cat ) : ?>
            <div class="form-check">
                <input
                    name="category_name"
                    id="filter-product-cat"
                    class="form-check-input"
                    value="<?php echo esc_attr( $cat->slug ); ?>"
                    type="checkbox"
                >
                <label class="form-check-label" for="product-content"><?php echo esc_html( $cat->name ) . '('. $cat->count .')'; ?></label>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div id="products-filter" class="card mb-2">
    <div class="card-header">
        Filter by price
    </div>
    <div class="card-body">
        <div class="input-group mb-3">
            <span class="input-group-text">min</span>
            <input name="min_price" value="0" type="number" step="1000" min="0" id="min-price" type="text" class="form-control" aria-label="Amount (min)">
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text">max</span>
            <input name="max_price" value="500000" type="number" step="1000" max="1000000000" id="max-price" type="text" class="form-control" aria-label="Amount (max)">
        </div>
    </div>
</div>

<div id="products-filter" class="card mb-2">
    <div class="card-header">
        Sort by total
    </div>
    <div class="card-body">
        <div class="input-group mb-3">
            <select name="orderby" class="form-select" id="filter-orderby">
                <option value="rand">Random</option>
                <option value="price">Price</option>
                <option value="sales">Sales</option>
                <option value="name">Name</option>
            </select>
        </div>
        <div class="input-group mb-3">
            <select name="order" class="form-select" id="filter-order">
                <option value="DESC">High to low</option>
                <option value="ASC">Low to high</option>
            </select>
        </div>
    </div>
</div>
