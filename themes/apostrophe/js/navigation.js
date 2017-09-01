/**
 * navigation.js
 *
 * Handles toggling the navigation menu for small screens.
 */
( function() {

	var toggleMenuButton;

	window.addEventListener( 'load', function() {
		var container, menu, len;

		container = document.getElementById( 'site-navigation' );
		if ( ! container ) {
			return;
		}

		toggleMenuButton = container.getElementsByClassName( 'menu-toggle' )[0];
		if ( 'undefined' === typeof toggleMenuButton ) {
			return;
		}

		menu = container.getElementsByTagName( 'ul' )[0];

		// Hide menu toggle button if menu is empty and return early.
		if ( 'undefined' === typeof menu ) {
			toggleMenuButton.style.display = 'none';
			return;
		}

		menu.setAttribute( 'aria-expanded', 'false' );

		if ( -1 === menu.className.indexOf( 'nav-menu' ) ) {
			menu.className += ' nav-menu';
		}

		// Watch every clickable element in the menu
		container.addEventListener( 'click', toggleMenu );

		// Get all the link elements within the menu.
		links    = menu.getElementsByTagName( 'a' );
		subMenus = menu.getElementsByTagName( 'ul' );

		// Set menu items with submenus to aria-haspopup="true".
		for ( i = 0, len = subMenus.length; i < len; i++ ) {
			subMenus[i].parentNode.setAttribute( 'aria-haspopup', 'true' );
		}

		// Each time a menu link is focused or blurred, toggle focus.
		for ( i = 0, len = links.length; i < len; i++ ) {
			links[i].addEventListener( 'focus', toggleFocus, true );
			links[i].addEventListener( 'blur', toggleFocus, true );
		}

		/**
		 * Sets or removes .focus class on an element.
		 */
		function toggleFocus() {
			var self = this;

			// Move up through the ancestors of the current link until we hit .nav-menu.
			while ( -1 === self.className.indexOf( 'nav-menu' ) ) {

				// On li elements toggle the class .focus.
				if ( 'li' === self.tagName.toLowerCase() ) {
					if ( -1 !== self.className.indexOf( 'focus' ) ) {
						self.className = self.className.replace( ' focus', '' );
					} else {
						self.className += ' focus';
					}
				}

				self = self.parentElement;
			}
		}

		// If our menu toggle button is non-existent (ie, we're on a large screen),
		// add JS for touchscreens, just in case.
		if ( 0 == toggleMenuButton.offsetWidth && 0 == toggleMenuButton.offsetHeight ) {
			/**
			 * Toggles `focus` class to allow submenu access on tablets.
			 */
			( function( container ) {
				var touchStartFn, i,
					parentLink = container.querySelectorAll( '.menu-item-has-children > a, .page_item_has_children > a' );

				if ( 'ontouchstart' in window ) {
					touchStartFn = function( e ) {
						var menuItem = this.parentNode, i;

						if ( ! menuItem.classList.contains( 'focus' ) ) {
							e.preventDefault();
							for ( i = 0; i < menuItem.parentNode.children.length; ++i ) {
								if ( menuItem === menuItem.parentNode.children[i] ) {
									continue;
								}
								menuItem.parentNode.children[i].classList.remove( 'focus' );
							}
							menuItem.classList.add( 'focus' );
						} else {
							menuItem.classList.remove( 'focus' );
						}
					};

					for ( i = 0; i < parentLink.length; ++i ) {
						parentLink[i].addEventListener( 'touchstart', touchStartFn, false );
					}
				}
			}( container ) );
		}

	} ); // page load

	function toggleMenu(event) {

		// If our menu toggle button is non-existent (ie, we're on a large screen), return early.
		if ( 0 == toggleMenuButton.offsetWidth && 0 == toggleMenuButton.offsetHeight ) {
			return;
		}

		var button = event.target;
		var parent = button.parentNode;

		// If we're not clicking on a button, don't do anything
		if ( -1 !== button.className.indexOf( 'menu-toggle' ) && -1 !== parent.className.indexOf( 'menu-item-has-children' ) ) {
			return;
		}

		// Toggle the menu
		if ( -1 !== parent.className.indexOf( 'toggled' ) ) {
			parent.className = parent.className.replace( ' toggled', '' );
			button.setAttribute( 'aria-expanded', 'false' );
			parent.setAttribute( 'aria-expanded', 'false' );
		} else {
			parent.className += ' toggled';
			button.setAttribute( 'aria-expanded', 'true' );
			parent.setAttribute( 'aria-expanded', 'true' );
		}

		// Links shouldn't work if the item has a sub-menu
		if ( -1 !== parent.className.indexOf( 'menu-item-has-children' ) ) {
			event.preventDefault();
		}
	}
} )();
