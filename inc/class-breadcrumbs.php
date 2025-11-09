<?php
/**
 * Breadcrumbs Class
 * Builds the Breadcrumbs
 *
 * @package MacrosBySara
 * @subpackage Blocks
 */

namespace MacrosBySara\Blocks;

/**
 * Breadcrumbs Class
 */
class Breadcrumbs {
	/**
	 * Separator symbol.
	 *
	 * @var 'slash'|'chevron'|'arrow' $separator
	 */
	private string $separator;

	/**
	 * Whether to show the Home link.
	 *
	 * @var bool $should_show_home
	 */
	private bool $should_show_home;

	/**
	 * Category selection strategy.
	 *
	 * @var 'first'|'deepest' $category_selection
	 */
	private string $category_selection;

	/**
	 * Categories of the current post.
	 *
	 * @var array $categories
	 */
	private array $categories;

	/**
	 * Constructor.
	 *
	 * @param array $attributes The block attributes.
	 * @param \WP_Term[] $categories The categories of the current post.
	 */
	public function __construct( array $attributes ,array $categories) {
		$this->should_show_home   = isset( $attributes['showHome'] ) ? $attributes['showHome'] : true;
		$this->category_selection = isset( $attributes['categorySelection'] ) ? $attributes['categorySelection'] : 'first';
		$separator                = isset( $attributes['separator'] ) ? $attributes['separator'] : 'slash';
		$this->separator          = $this->get_separator( $separator );
		$this->categories         = $categories;
	}

	/**
	 * Get the separator symbol based on the block attributes.
	 *
	 * @param string $separator The separator type.
	 * @return string The separator symbol.
	 */
	private function get_separator( string $separator ): string {
		if ( ! in_array( $separator, array( 'slash', 'chevron', 'arrow' ), true ) ) {
			$separator = 'slash';
		}
		$map = array(
			'slash'   => '/',
			'chevron' => '›',
			'arrow'   => '→',
		);
		return $map[ $separator ];
	}

	/**
	 * Get all ancestor categories for a given category.
	 *
	 * @param int $category_id The category ID.
	 * @return array Array of category objects.
	 */
	private function get_category_ancestors( int $category_id ): array {
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

	/**
	 * Get the selected category based on the selection strategy.
	 *
	 * @return object|null The selected category or null.
	 */
	private function get_selected_category() {
		if ( empty( $this->categories ) ) {
			return null;
		}

		if ( 'deepest' === $this->category_selection ) {
			$deepest   = null;
			$max_depth = -1;

			foreach ( $this->categories as $category ) {
				$ancestors = $this->get_category_ancestors( $category->term_id );
				$depth     = count( $ancestors );

				if ( $depth > $max_depth ) {
					$max_depth = $depth;
					$deepest   = $category;
				}
			}

			return $deepest;
		}

		// Default to first category
		return $this->categories[0];
	}

	/**
	 * Build the breadcrumb items array.
	 *
	 * @return array The breadcrumb items.
	 */
	private function build_the_breadcrumb_array(): array {
		$items = array();
		if ( $this->should_show_home ) {
			$items[] = array(
				'url'        => home_url( '/' ),
				'title'      => __( 'Home', 'breadcrumbs-navigation' ),
				'is_current' => false,
			);
		}
		if ( ! empty( $this->categories ) ) {
			// Select category based on strategy
			$selected_category = $this->get_selected_category();

			if ( $selected_category ) {
				// Get ancestors
				$ancestors = $this->get_category_ancestors( $selected_category->term_id );

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
		return $items;
	}

	/**
	 * Get the breadcrumbs HTML.
	 *
	 * @return string The breadcrumbs HTML.
	 */
	public function get_the_breadcrumbs(): string {
		$items = $this->build_the_breadcrumb_array();
		// Generate breadcrumb HTML
		$breadcrumb_html = '';
		$item_count      = count( $items );
		foreach ( $items as $index => $item ) {
			$is_last = ( $index === $item_count - 1 );
			$inner_html = '';
			if ( $item['is_current'] || $is_last ) {
				$inner_html .= '<span class="breadcrumb-item breadcrumb-current">' . esc_html( $item['title'] ) . '</span>';
			} else {
				$inner_html .= '<span class="breadcrumb-item"><a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['title'] ) . '</a></span>';
			}

			// Add separator if not last item
			if ( ! $is_last ) {
				$inner_html .= '<span class="breadcrumb-separator" aria-hidden="true"> ' . esc_html( $this->separator ) . ' </span>';
			}
			$breadcrumb_html .= '<li>' . $inner_html . '</li>';
		}
		return $breadcrumb_html;
	}

	/**
	 * Output the breadcrumbs HTML.
	 */
	public function the_breadcrumbs(): void {
		echo wp_kses_post( $this->get_the_breadcrumbs() );
	}
}
