/* eslint-disable no-var */
/* global Flickity */
/**
 * File kb-carousel-init.js.
 * Gets carousel working for post carousel blocks.
 */
( function() {
	'use strict';
	var kadenceBlocksProCarousel = {
		cache: [],
		/**
		 * Initiate the script to process all
		 */
		initAll: function() {
			var carousels = document.querySelectorAll( '.kb-flickity-carousel-wrap' );
			for ( let i = 0; i < carousels.length; i++ ) {
				kadenceBlocksProCarousel.cache[ i ] = new Flickity( carousels[ i ], {
					// options
					cellAlign: 'left',
					wrapAround: true
				} );
			}
		},
		// Initiate the menus when the DOM loads.
		init: function() {
			if ( typeof Flickity == 'function' ) {
				kadenceBlocksProCarousel.initAll();
			} else {
				// eslint-disable-next-line vars-on-top
				var initLoadDelay = setInterval( function() {
					if ( typeof Flickity == 'function' ) {
						kadenceBlocksProCarousel.initAll();
						clearInterval( initLoadDelay );
					}
				}, 200 );
			}
		},
	};
	if ( 'loading' === document.readyState ) {
		// The DOM has not yet been loaded.
		document.addEventListener( 'DOMContentLoaded', kadenceBlocksProCarousel.init );
	} else {
		// The DOM has already been loaded.
		kadenceBlocksProCarousel.init();
	}
}() );
