import { useBlockProps } from '@wordpress/block-editor';
import { Spinner } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';

import BlockControls from './BlockControls';
import useCategories from './hooks/useCategories';
import { useSelect } from '@wordpress/data';
import { store as editorStore } from '@wordpress/editor';

export default function Edit( props ) {
	const { showHome, separator, categorySelection } = props.attributes;
	const { categoryDetails, allCategories, isResolving } = useCategories();
	const [ selectedCategory, setSelectedCategory ] = useState< string | null >(
		null
	);
	const [ items, setItems ] = useState< string[] >( [] );
	const postTitle = useSelect( ( select ) => {
		return select( editorStore ).getCurrentPost()?.title;
	}, [] );
	const blockProps = useBlockProps();
	const separatorSymbol = getSeparatorSymbol( separator );

	useEffect( () => {
		if ( categoryDetails && categoryDetails.length > 0 ) {
			if ( categorySelection === 'deepest' ) {
				// Find the category with the most ancestors
				setSelectedCategory( () =>
					categoryDetails.reduce( ( deepest, cat ) => {
						const depth = cat.parent
							? getAncestorDepth( cat, allCategories )
							: 0;
						const deepestDepth = deepest
							? deepest.parent
								? getAncestorDepth( deepest, allCategories )
								: 0
							: -1;
						return depth > deepestDepth ? cat.name : deepest.name;
					}, null )
				);
			} else {
				// Use first category
				setSelectedCategory( categoryDetails[ 0 ].name );
			}
		}
	}, [ categoryDetails, allCategories, categorySelection ] );

	useEffect( () => {
		if ( selectedCategory ) {
			const ancestors = getAncestors( selectedCategory, allCategories );
			setItems( [ ...ancestors.reverse(), selectedCategory ] );
		}
	}, [ selectedCategory, allCategories ] );

	return (
		<>
			<BlockControls { ...props } categoryDetails={ categoryDetails } />
			{ isResolving && <Spinner /> }
			{ ! isResolving && (
				<nav { ...blockProps } aria-label="Breadcrumb">
					<ol className="breadcrumb-list">
						{ showHome && (
							<li>
								<span className="breadcrumb-item">Home</span>
								<span className="breadcrumb-separator">
									{ separatorSymbol }
								</span>
							</li>
						) }
						{ items.map( ( item, index ) => (
							<li key={ index }>
								<span className="breadcrumb-item">
									{ item }
								</span>
							</li>
						) ) }
						<li>
							<span className="breadcrumb-separator">
								{ separatorSymbol }
							</span>
							<span
								className="breadcrumb-item"
								dangerouslySetInnerHTML={ {
									__html: postTitle,
								} }
							/>
						</li>
					</ol>
				</nav>
			) }
		</>
	);
}

function getAncestors( category, allCats ): string[] {
	const ancestors = [];
	let current = category;

	while ( current.parent ) {
		const parent = allCats.find( ( cat ) => cat.id === current.parent );
		if ( parent ) {
			ancestors.push( parent.name );
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
