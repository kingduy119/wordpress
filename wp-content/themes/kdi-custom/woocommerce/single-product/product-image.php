<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.7.0
 */

use Automattic\WooCommerce\Enums\ProductType;

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$post_thumbnail_id = $product->get_image_id();
$gallery_ids = $product->get_gallery_image_ids(); // gallery phụ

?>

<!-- Ảnh chính -->
<div class="mb-3">
	<img id="mainProductImage"
		src="<?php echo wp_get_attachment_url($post_thumbnail_id); ?>"
		class="img-fluid border rounded"
		alt="Ảnh sản phẩm chính" />
</div>

<!-- Gallery ảnh phụ -->
<div class="d-flex flex-wrap gap-2">
	<!-- Ảnh featured cũng cho vào thumbnail để có thể chọn lại -->
	<img src="<?php echo wp_get_attachment_url($post_thumbnail_id); ?>"
		class="img-thumbnail product-thumb"
		style="width: 80px; cursor: pointer;"
		data-full="<?php echo wp_get_attachment_url($post_thumbnail_id); ?>" />

	<?php foreach ($gallery_ids as $img_id) : ?>
	<img src="<?php echo wp_get_attachment_url($img_id); ?>"
		class="img-thumbnail product-thumb"
		style="width: 80px; cursor: pointer;"
		data-full="<?php echo wp_get_attachment_url($img_id); ?>" />
	<?php endforeach; ?>
</div>


<script>
  // Khi ảnh nhỏ được click, thay ảnh chính
  document.addEventListener('DOMContentLoaded', function () {
    const mainImage = document.getElementById('mainProductImage');
    const thumbs = document.querySelectorAll('.product-thumb');

    thumbs.forEach(function (thumb) {
      thumb.addEventListener('click', function () {
        const newSrc = this.getAttribute('data-full');
        mainImage.src = newSrc;
      });
    });
  });
</script>