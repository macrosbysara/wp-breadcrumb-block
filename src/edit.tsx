/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor';

import Breadcrumb from './Breadcrumb';
import BlockControls from './BlockControls';
import useCategories from './hooks/useCategories';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @param {Object}   props               Block props.
 * @param {Object}   props.attributes    Block attributes.
 * @param {Function} props.setAttributes Function to set attributes.
 * @return {Element} Element to render.
 */
export default function Edit( props ) {
	const { showHome, separator, categorySelection } = props.attributes;
	const blockProps = useBlockProps();
	const { categoryDetails, allCategories } = useCategories();
	return (
		<>
			<BlockControls { ...props } categoryDetails={ categoryDetails } />
			<nav { ...blockProps } aria-label="Breadcrumb">
				<ol className="breadcrumb-list">
					<li>
						<Breadcrumb
							categorySelection={ categorySelection }
							allCategories={ allCategories }
							categoryDetails={ categoryDetails }
							separator={ separator }
							shouldShowHome={ showHome }
						/>
					</li>
				</ol>
			</nav>
		</>
	);
}
