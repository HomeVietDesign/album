window.addEventListener('DOMContentLoaded', function(){
	let initPhotoSwipeFromDOM = function(gallerySelector) {

		let openPhotoSwipe = function(galleryElement) {
			let pswpElement = document.querySelectorAll('.pswp')[0];
			let images = JSON.parse(galleryElement.dataset.images);
			
			// build items array
			let items = [];

			for (var i = 0; i < images.length; i++) {
				let item = {
					src: images[i][0],
					w: images[i][1],
					h: images[i][2]
				}

				items.push(item);
			}

			// define options (if needed)
			let options = {
				// history & focus options are disabled on CodePen        
				history: false,
				focus: false,

				showAnimationDuration: 0,
				hideAnimationDuration: 0

			};

			let gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
			gallery.init();

		};

		// select all gallery elements
		let galleryElements = document.querySelectorAll( gallerySelector );
		for(let i = 0, l = galleryElements.length; i < l; i++) {
			galleryElements[i].setAttribute('data-pswp-uid', i+1);
			galleryElements[i].getElementsByTagName('IMG')[0].onclick = function(){
				openPhotoSwipe(galleryElements[i]);
			}
		}
	};

	// execute above function
	initPhotoSwipeFromDOM('.photowipe-gallery-shortcode');
});
