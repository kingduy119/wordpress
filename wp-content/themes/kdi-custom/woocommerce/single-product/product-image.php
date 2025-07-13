<?php
defined( 'ABSPATH' ) || exit;

global $product;

$thumbnail_id     = $product->get_image_id();
$thumbnail_image  = wp_get_attachment_url( $thumbnail_id );
$placeholder_image = wc_placeholder_img_src();
$main_image        = $thumbnail_image ? $thumbnail_image : $placeholder_image;

$gallery_ids = $product->get_gallery_image_ids();
?>

<!-- Ảnh chính -->
<div class="mb-3">
	<img
		src="<?php echo esc_url( $main_image ); ?>"
		class="single-product__thumbnail"
		alt="Main image product" />
</div>

<?php if ( $thumbnail_image && count( $gallery_ids ) > 0 ) : ?>
<!-- Gallery ảnh phụ -->
<div class="d-flex flex-wrap gap-2">
	<!-- Ảnh featured cũng cho vào thumbnail -->
	<img src="<?php echo esc_url( $main_image ); ?>"
		class="img-thumbnail product-thumb"
		style="width: 80px; cursor: pointer;"
		data-full="<?php echo esc_url( $main_image ); ?>" />

	<?php foreach ( $gallery_ids as $img_id ) : 
		$img_url = wp_get_attachment_url( $img_id );
		if ( ! $img_url ) continue;
	?>
		<img src="<?php echo esc_url( $img_url ); ?>"
			class="img-thumbnail product-thumb"
			style="width: 80px; cursor: pointer;"
			data-full="<?php echo esc_url( $img_url ); ?>" />
	<?php endforeach; ?>
</div>
<?php endif; ?>

<script>
  // Khi ảnh nhỏ được click, thay ảnh chính
  document.addEventListener('DOMContentLoaded', function () {
    const mainImage = document.getElementById('mainProductImage');
    const thumbs = document.querySelectorAll('.product-thumb');

    thumbs.forEach(function (thumb) {
      thumb.addEventListener('click', function () {
        const newSrc = this.getAttribute('data-full');
        if (newSrc) mainImage.src = newSrc;
      });
    });
  });
</script>
