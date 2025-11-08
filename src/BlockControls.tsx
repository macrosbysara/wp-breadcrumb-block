import { InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	ToggleControl,
	SelectControl,
	Notice,
} from '@wordpress/components';

export default function BlockControls( {
	attributes,
	setAttributes,
	categoryDetails,
} ) {
	const { showHome, separator, categorySelection } = attributes;

	return (
		<InspectorControls>
			<PanelBody title="Breadcrumb Settings">
				<ToggleControl
					__nextHasNoMarginBottom
					label="Show Home Link"
					help='Display "Home" as the first breadcrumb item'
					checked={ showHome }
					onChange={ ( value ) =>
						setAttributes( { showHome: value } )
					}
				/>
				<SelectControl
					__next40pxDefaultSize
					__nextHasNoMarginBottom
					label="Separator Style"
					value={ separator }
					options={ [
						{
							label: 'Forward Slash (/)',
							value: 'slash',
						},
						{
							label: 'Chevron (›)',
							value: 'chevron',
						},
						{
							label: 'Arrow (→)',
							value: 'arrow',
						},
					] }
					onChange={ ( value ) =>
						setAttributes( { separator: value } )
					}
				/>
			</PanelBody>
			<PanelBody title="Category Settings" initialOpen={ true }>
				<SelectControl
					__next40pxDefaultSize
					__nextHasNoMarginBottom
					label="Category Selection Strategy"
					help="Choose which category to display when multiple categories are assigned to a post"
					value={ categorySelection }
					options={ [
						{
							label: 'First Assigned Category',
							value: 'first',
						},
						{
							label: 'Deepest Category Hierarchy',
							value: 'deepest',
						},
					] }
					onChange={ ( value ) =>
						setAttributes( { categorySelection: value } )
					}
				/>

				{ ( ! categoryDetails || categoryDetails.length === 0 ) && (
					<Notice status="warning" isDismissible={ false }>
						<p style={ { margin: 0 } }>
							This post has no categories assigned. The breadcrumb
							will only show Home (if enabled) and the current
							post.
						</p>
					</Notice>
				) }
			</PanelBody>
		</InspectorControls>
	);
}
