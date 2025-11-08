import { useState } from '@wordpress/element';
export default function Breadcrumb( {
	categorySelection,
	separator,
	shouldShowHome,
	categoryDetails,
	allCategories,
} ) {
	const [ selectedCategory, setSelectedCategory ] = useState( null );
	const [ items, setItems ] = useState( [] );
	const separatorSymbol = getSeparatorSymbol( separator );

	if ( shouldShowHome ) {
		items.push(
			<span key="home" className="breadcrumb-item">
				<a href="#">Home</a>
			</span>
		);
	}

	if ( categoryDetails && categoryDetails.length > 0 ) {
		// Build category hierarchy
		let selectedCategory;

		if ( categorySelection === 'deepest' ) {
			// Find the category with the most ancestors
			selectedCategory = categoryDetails.reduce( ( deepest, cat ) => {
				const depth = cat.parent
					? getAncestorDepth( cat, allCategories )
					: 0;
				const deepestDepth = deepest
					? deepest.parent
						? getAncestorDepth( deepest, allCategories )
						: 0
					: -1;
				return depth > deepestDepth ? cat : deepest;
			}, null );
		} else {
			// Use first category
			selectedCategory = categoryDetails[ 0 ];
		}

		if ( selectedCategory ) {
			const ancestors = getAncestors( selectedCategory, allCategories );

			// Add ancestor categories
			ancestors.reverse().forEach( ( cat ) => {
				items.push(
					<span
						key={ `sep-${ cat.id }` }
						className="breadcrumb-separator"
						aria-hidden="true"
					>
						{ ' ' + separatorSymbol + ' ' }
					</span>
				);
				items.push(
					<span key={ cat.id } className="breadcrumb-item">
						<a href="#">{ cat.name }</a>
					</span>
				);
			} );

			// Add selected category
			items.push(
				<span
					key={ `sep-${ selectedCategory.id }` }
					className="breadcrumb-separator"
					aria-hidden="true"
				>
					{ ' ' + separatorSymbol + ' ' }
				</span>
			);
			items.push(
				<span key={ selectedCategory.id } className="breadcrumb-item">
					<a href="#">{ selectedCategory.name }</a>
				</span>
			);
		}
	}

	// Add current post
	items.push(
		<span
			key="sep-current"
			className="breadcrumb-separator"
			aria-hidden="true"
		>
			{ ' ' + separatorSymbol + ' ' }
		</span>
	);
	items.push(
		<span key="current" className="breadcrumb-item breadcrumb-current">
			Current Post
		</span>
	);

	return items;
}

function getSeparatorSymbol( separator ) {
	switch ( separator ) {
		case 'chevron':
			return '›';
		case 'arrow':
			return '→';
		case 'slash':
		default:
			return '/';
	}
}

function getAncestors( category, allCats ) {
	const ancestors = [];
	let current = category;

	while ( current.parent ) {
		const parent = allCats.find( ( cat ) => cat.id === current.parent );
		if ( parent ) {
			ancestors.push( parent );
			current = parent;
		} else {
			break;
		}
	}

	return ancestors;
}

function getAncestorDepth( category, allCats ) {
	return getAncestors( category, allCats ).length;
}
