<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$total		= isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
$current	= isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
$per_page	= isset( $per_page ) ? $per_page : wc_get_loop_prop( 'per_page' );

if ( $total <= 1 ) { return; }
?>

<ul class="pagination">
<?php
	for( $i = 1; $i <= $total; $i++) {
		$disable = ( $current == $i ) ? 'disabled' : '';
		echo '
		<li class="page-item '.$disable.'">
			<a class="page-link" href="?product-page='.$i.'" per-page='.$per_page.'>'.$i.'</a>
		</li>
		';
	}
?>
</ul>
