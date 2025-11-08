import { store as editorStore } from '@wordpress/editor';
import { store as coreStore } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';

export default function useCategories() {
	const { categoryDetails, allCategories } = useSelect( ( select ) => {
		const coreDataStore = select( coreStore );

		const post = select( editorStore ).getCurrentPost();
		const postCategories = post?.categories || [];

		// Fetch all categories to build full hierarchy
		const allCategories =
			coreDataStore.getEntityRecords( 'taxonomy', 'category', {
				per_page: -1,
			} ) || [];

		if ( postCategories.length === 0 ) {
			return {
				categoryDetails: [],
			};
		}

		const details = coreDataStore.getEntityRecords(
			'taxonomy',
			'category',
			{
				include: postCategories,
				per_page: -1,
			}
		);

		return {
			allCategories,
			categoryDetails: details || [],
		};
	}, [] );
	return { categoryDetails, allCategories };
}
