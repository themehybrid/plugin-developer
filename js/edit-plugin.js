jQuery( document ).ready( function() {

	/* === Edit sticky status in the "Publish" meta box. === */

	var sticky_checkbox = jQuery( 'input[name=pdev_plugin_sticky]' );
	var is_sticky       = jQuery( sticky_checkbox ).prop( 'checked' );

	// When user clicks the "Edit" sticky link.
	jQuery( 'a.pdev-edit-sticky' ).click(
		function( j ) {
			j.preventDefault();

			// Grab the original status again in case user clicks "OK" or "Cancel" more than once.
			is_sticky = jQuery( sticky_checkbox ).prop( 'checked' );

			// Hide this link.
			jQuery( this ).hide();

			// Open the sticky edit.
			jQuery( '#pdev-sticky-edit' ).slideToggle( 'fast' );
		}
	);

	/* When the user clicks the "OK" post status button. */
	jQuery( 'a.pdev-save-sticky' ).click(
		function( j ) {
			j.preventDefault();

			// Close the sticky edit.
			jQuery( '#pdev-sticky-edit' ).slideToggle( 'fast' );

			// Show the hidden "Edit" link.
			jQuery( 'a.pdev-edit-sticky' ).show();
		}
	);

	// When the user clicks the "Cancel" edit sticky link.
	jQuery( 'a.pdev-cancel-sticky' ).click(
		function( j ) {
			j.preventDefault();

			// Close the sticky edit.
			jQuery( '#pdev-sticky-edit' ).slideToggle( 'fast' );

			// Show the hidden "Edit" link.
			jQuery( 'a.pdev-edit-sticky' ).show();

			// Set the original checked/not-checked since we're canceling.
			jQuery( sticky_checkbox ).prop( 'checked', is_sticky ).trigger( 'change' );
		}
	);

	// When the sticky status changes.
	jQuery( sticky_checkbox ).change(
		function() {
			jQuery( 'strong.pdev-sticky-status' ).text(
				jQuery( sticky_checkbox ).prop( 'checked' ) ? pdev_i18n.label_sticky : pdev_i18n.label_not_sticky
			);
		}
	);

	/* === Tabs === */

	// Hides the tab content.
	jQuery( '.pdev-fields-section' ).hide();

	// Shows the first tab's content.
	jQuery( '.pdev-fields-section:first-child' ).show();

	// Makes the 'aria-selected' attribute true for the first tab nav item.
	jQuery( '.pdev-fields-nav :first-child' ).attr( 'aria-selected', 'true' );

	// Copies the current tab item title to the box header.
	jQuery( '.pdev-which-tab' ).text( jQuery( '.pdev-fields-nav :first-child a' ).text() );

	// When a tab nav item is clicked.
	jQuery( '.pdev-fields-nav li a' ).click(
		function( j ) {

			// Prevent the default browser action when a link is clicked.
			j.preventDefault();

			// Get the `href` attribute of the item.
			var href = jQuery( this ).attr( 'href' );

			// Hide all tab content.
			jQuery( this ).parents( '.pdev-fields-manager' ).find( '.pdev-fields-section' ).hide();

			// Find the tab content that matches the tab nav item and show it.
			jQuery( this ).parents( '.pdev-fields-manager' ).find( href ).show();

			// Set the `aria-selected` attribute to false for all tab nav items.
			jQuery( this ).parents( '.pdev-fields-manager' ).find( '.pdev-fields-nav li' ).attr( 'aria-selected', 'false' );

			// Set the `aria-selected` attribute to true for this tab nav item.
			jQuery( this ).parent().attr( 'aria-selected', 'true' );

			// Copy the current tab item title to the box header.
			jQuery( '.pdev-which-tab' ).text( jQuery( this ).text() );
		}
	); // click()

	/* === Begin icon image JS. === */

	// If the icon <img> source has a value, show it.  Otherwise, hide.
	if ( jQuery( '.pdev-icon-image-url' ).attr( 'src' ) ) {
		jQuery( '.pdev-icon-image-url' ).show();
	} else {
		jQuery( '.pdev-icon-image-url' ).hide();
	}

	var pdev_icon_id = jQuery( 'input#pdev-icon-image' ).val();

	// If there's a value for the icon image input.
	if ( pdev_icon_id && 0 != pdev_icon_id ) {

		// Hide the 'set icon image' link.
		jQuery( '.pdev-add-media-text' ).hide();

		// Show the 'remove icon image' link, the image.
		jQuery( '.pdev-remove-media, .pdev-icon-image-url' ).show();
	}

	// Else, if there's not a value for the icon image input.
	else {

		// Show the 'set icon image' link.
		jQuery( '.pdev-add-media-text' ).show();

		// Hide the 'remove icon image' link, the image.
		jQuery( '.pdev-remove-media, .pdev-icon-image-url' ).hide();
	}

	// When the 'remove icon image' link is clicked.
	jQuery( '.pdev-remove-media' ).click(
		function( j ) {

			// Prevent the default link behavior.
			j.preventDefault();

			// Set the icon image input value to nothing.
			jQuery( '#pdev-icon-image' ).val( '' );

			// Show the 'set icon image' link.
			jQuery( '.pdev-add-media-text' ).show();

			// Hide the 'remove icon image' link, the image.
			jQuery( '.pdev-remove-media, .pdev-icon-image-url' ).hide();
		}
	);

	/*
	 * The following code deals with the custom media modal frame for the icon image.  It is a
	 * modified version of Thomas Griffin's New Media Image Uploader example plugin.
	 *
	 * @link      https://github.com/thomasgriffin/New-Media-Image-Uploader
	 * @license   http://www.opensource.org/licenses/gpl-license.php
	 * @author    Thomas Griffin <thomas@thomasgriffinmedia.com>
	 * @copyright Copyright 2013 Thomas Griffin
	 */

	// Prepare the variable that holds our custom media manager.
	var pdev_icon_frame;

	// When the 'set header image' link is clicked.
	jQuery( '.pdev-add-media' ).click(

		function( j ) {

			// Prevent the default link behavior.
			j.preventDefault();

			// If the frame already exists, open it.
			if ( pdev_icon_frame ) {
				pdev_icon_frame.open();
				return;
			}

			// Creates a custom media frame.
			pdev_icon_frame = wp.media.frames.pdev_icon_frame = wp.media(
				{
					className: 'media-frame',              // Custom CSS class name
					frame:     'select',                   // Frame type (post, select)
					multiple:  false,                      // Allow selection of multiple images
					title:     pdev_i18n.label_icon_title, // Custom frame title

					library: {
						type: 'image' // Media types allowed
					},

					button: {
						text:  pdev_i18n.label_icon_button // Custom insert button text
					}
				}
			);

			// The following handles the image data and sending it back to the meta box once an
			// an image has been selected via the media frame.
			pdev_icon_frame.on( 'select',

				function() {

					// Construct a JSON representation of the model.
					var media_attachment = pdev_icon_frame.state().get( 'selection' ).toJSON();

					var pdev_media_url    = media_attachment[0].sizes.full.url;
					var pdev_media_width  = media_attachment[0].sizes.full.width;
					var pdev_media_height = media_attachment[0].sizes.full.height;

					// Add the image attachment ID to our hidden form field.
					jQuery( '#pdev-icon-image').val( media_attachment[0].id );

					// Change the 'src' attribute so the image will display in the meta box.
					jQuery( '.pdev-icon-image-url' ).attr( 'src', pdev_media_url );

					// Hides the add media link.
					jQuery( '.pdev-add-media-text' ).hide();

					// Displays the image and remove link.
					jQuery( '.pdev-icon-image-url, .pdev-remove-media' ).show();
				}
			);

			// Open up the frame.
			pdev_icon_frame.open();
		}
	);

	/* === End icon image JS. === */

} ); // ready()
