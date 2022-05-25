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