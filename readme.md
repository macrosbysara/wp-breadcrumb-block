# Breadcrumbs Navigation

**Contributors:** WordPress Telex  
**Tags:** block, breadcrumbs, navigation, categories, hierarchy  
**Tested up to:** 6.8  
**Stable tag:** 0.1.0  
**License:** GPLv2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html

A flexible breadcrumbs block that displays hierarchical navigation trails based on post categories with customizable settings and full accessibility support.

---

## Description

The Breadcrumbs Navigation block provides an intuitive way to display breadcrumb trails on your WordPress site. It automatically generates a navigation path based on the current post's categories, making it easy for visitors to understand where they are in your site's hierarchy and navigate back to parent pages.

### Key Features

- **Automatic Hierarchy Detection**: Intelligently displays the category hierarchy starting from Home
- **Customizable Separators**: Choose between forward slash (/), chevron (›), or arrow (→) separators
- **Toggle Home Link**: Option to show or hide the Home link at the start of the trail
- **Category Selection**: Control which category to use when multiple categories are assigned
- **Responsive Design**: Works beautifully on all screen sizes
- **Accessibility First**: Built with semantic HTML and proper ARIA labels
- **Live Preview**: See breadcrumb structure directly in the block editor
- **Smart Fallback**: Gracefully handles posts without categories

### How It Works

The breadcrumb trail is constructed in the following order:

1. Home link (optional)
2. Parent categories (if hierarchical)
3. Primary or selected category
4. Current post title (non-clickable)

Each breadcrumb item is clickable except for the current page, which appears as plain text to indicate the user's current location.

---

## Installation

1. Upload the plugin files to the `/wp-content/plugins/breadcrumbs-navigation` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Add the Breadcrumbs Navigation block to any post or page using the block editor
4. Customize the block settings in the sidebar to match your preferences

---

## Frequently Asked Questions

### Does this work with custom post types?
Yes! The block works with any post type that supports categories. It will display the category hierarchy for the current post.

### What happens if a post has no categories?
The breadcrumb will show only the Home link (if enabled) followed by the current post title.

### Can I customize the separator?
Absolutely! You can choose from three separator styles: forward slash (/), chevron (›), or arrow (→).

### What if a post has multiple categories?
You can choose to display either the first assigned category or let the block automatically select the primary category based on hierarchy depth.

### Is the block accessible?
Yes, the block is built with accessibility in mind, including proper semantic HTML5 nav elements and ARIA labels for screen readers.

---

## Screenshots

1. Breadcrumbs block in the editor with live preview
2. Block settings panel showing customization options
3. Frontend display with hierarchical category path
4. Responsive design on mobile devices

---

## Usage

After installing and activating the plugin:

1. Edit any post or page in the block editor
2. Click the '+' button to add a new block
3. Search for "Breadcrumbs Navigation"
4. The block will automatically display the breadcrumb trail
5. Use the sidebar settings to customize:
   - Toggle the Home link on/off
   - Select separator style
   - Choose category display preference

The breadcrumbs will automatically update on the frontend based on the current post's categories and hierarchy.
