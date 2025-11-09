import { store as editorStore } from '@wordpress/editor';
import { store as coreStore, Term } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';

export default function useCategories() {
	const { postCategories } = useSelect( ( select ) => {
		const postCategories =
			select( editorStore ).getCurrentPost()?.categories;
		return { postCategories };
	}, [] );

	const { categoryDetails, allCategories, isResolving } = useSelect(
		( select ) => {
			const core = select( coreStore );
			const allCategories: Term[] | null = core.getEntityRecords(
				'taxonomy',
				'category',
				{
					per_page: -1,
				}
			);
			const categoryDetails: Term[] | null = core.getEntityRecords(
				'taxonomy',
				'category',
				{
					include: postCategories,
				}
			);

			const isResolvingAll = core.isResolving( 'getEntityRecords', [
				'taxonomy',
				'category',
				{ per_page: -1 },
			] );
			const isResolvingDetails = core.isResolving( 'getEntityRecords', [
				'taxonomy',
				'category',
				{ include: postCategories },
			] );

			return {
				allCategories,
				categoryDetails,
				isResolving: isResolvingAll || isResolvingDetails,
			};
		},
		[ postCategories ]
	);

	return { categoryDetails, allCategories, isResolving };
}
