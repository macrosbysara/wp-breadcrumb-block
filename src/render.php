<?php
/**
 * Render Callback for Breadcrumbs Navigation Block.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 *
 * @package MacrosBySara
 */

if ( ! function_exists( 'breadcrumbs_navigation_get_separator' ) ) {
	/**
	 * Get the separator symbol based on the block attributes.
	 *
	 * @param string $separator The separator type.
	 * @return string The separator symbol.
	 */
	function breadcrumbs_navigation_get_separator( $separator ) {
		switch ( $separator ) {
			case 'chevron':
				return '›';
			case 'arrow':
				return '→';
			case 'slash':
			default:
				return '/';
		}
	}
}

if ( ! function_exists( 'breadcrumbs_navigation_get_category_ancestors' ) ) {
	/**
	 * Get all ancestor categories for a given category.
	 *
	 * @param int $category_id The category ID.
	 * @return array Array of category objects.
	 */
	function breadcrumbs_navigation_get_category_ancestors( $category_id ) {
		$ancestors  = array();
		$current_id = $category_id;

		while ( $current_id ) {
			$category = get_category( $current_id );
			if ( ! $category || is_wp_error( $category ) ) {
				break;
			}

			if ( $category->parent ) {
				$parent = get_category( $category->parent );
				if ( $parent && ! is_wp_error( $parent ) ) {
					$ancestors[] = $parent;
					$current_id  = $parent->term_id;
				} else {
					break;
				}
			} else {
				break;
			}
		}

		return array_reverse( $ancestors );
	}
}

if ( ! function_exists( 'breadcrumbs_navigation_get_selected_category' ) ) {
	/**
	 * Get the selected category based on the selection strategy.
	 *
	 * @param array  $categories The array of category objects.
	 * @param string $strategy The selection strategy ('first' or 'deepest').
	 * @return object|null The selected category or null.
	 */
	function breadcrumbs_navigation_get_selected_category( $categories, $strategy ) {
		if ( empty( $categories ) ) {
			return null;
		}

		if ( $strategy === 'deepest' ) {
			$deepest   = null;
			$max_depth = -1;

			foreach ( $categories as $category ) {
				$ancestors = breadcrumbs_navigation_get_category_ancestors( $category->term_id );
				$depth     = count( $ancestors );

				if ( $depth > $max_depth ) {
					$max_depth = $depth;
					$deepest   = $category;
				}
			}

			return $deepest;
		}

		// Default to first category
		return $categories[0];
	}
}

// Get block attributes
$show_home          = isset( $attributes['showHome'] ) ? $attributes['showHome'] : true;
$separator          = isset( $attributes['separator'] ) ? $attributes['separator'] : 'slash';
$category_selection = isset( $attributes['categorySelection'] ) ? $attributes['categorySelection'] : 'first';

// Get separator symbol
$separator_symbol = breadcrumbs_navigation_get_separator( $separator );

// Build breadcrumb items
$items = array();

// Add Home link
if ( $show_home ) {
	$items[] = array(
		'url'        => home_url( '/' ),
		'title'      => __( 'Home', 'breadcrumbs-navigation' ),
		'is_current' => false,
	);
}

// Get post categories
$categories = get_the_category( get_the_ID() );

if ( ! empty( $categories ) ) {
	// Select category based on strategy
	$selected_category = breadcrumbs_navigation_get_selected_category( $categories, $category_selection );

	if ( $selected_category ) {
		// Get ancestors
		$ancestors = breadcrumbs_navigation_get_category_ancestors( $selected_category->term_id );

		// Add ancestor categories
		foreach ( $ancestors as $ancestor ) {
			$items[] = array(
				'url'        => get_category_link( $ancestor->term_id ),
				'title'      => $ancestor->name,
				'is_current' => false,
			);
		}

		// Add selected category
		$items[] = array(
			'url'        => get_category_link( $selected_category->term_id ),
			'title'      => $selected_category->name,
			'is_current' => false,
		);
	}
}

// Add current post
$items[] = array(
	'url'        => '',
	'title'      => get_the_title(),
	'is_current' => true,
);

// Generate breadcrumb HTML
$breadcrumb_html = '';
$item_count      = count( $items );

foreach ( $items as $index => $item ) {
	$is_last = ( $index === $item_count - 1 );

	if ( $item['is_current'] || $is_last ) {
		$breadcrumb_html .= '<span class="breadcrumb-item breadcrumb-current">' . esc_html( $item['title'] ) . '</span>';
	} else {
		$breadcrumb_html .= '<span class="breadcrumb-item"><a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['title'] ) . '</a></span>';
	}

	// Add separator if not last item
	if ( ! $is_last ) {
		$breadcrumb_html .= '<span class="breadcrumb-separator" aria-hidden="true"> ' . esc_html( $separator_symbol ) . ' </span>';
	}
}
?>

<nav <?php echo get_block_wrapper_attributes(); ?> aria-label="Breadcrumb">
	<ol class="breadcrumb-list">
		<li><?php echo wp_kses_post( $breadcrumb_html ); ?></li>
	</ol>
</nav>
