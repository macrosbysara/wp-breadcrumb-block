<?php
/**
 * Render Callback for Breadcrumbs Navigation Block.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 *
 * @package MacrosBySara
 */

use MacrosBySara\Blocks\Breadcrumbs;


$breadcrumbs = new Breadcrumbs( $attributes, get_the_category( get_the_ID() ) );
?>

<nav <?php echo get_block_wrapper_attributes(); ?> aria-label="Breadcrumb">
	<ol class="breadcrumb-list">
		<?php $breadcrumbs->the_breadcrumbs(); ?>
	</ol>
</nav>
