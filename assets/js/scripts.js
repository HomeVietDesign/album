window.addEventListener('DOMContentLoaded', function(){
	
	lazyLoadImages(document);
	
	const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
	const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

	function setCookie(cname, cvalue, exdays) {
		const d = new Date();
		d.setTime(d.getTime() + (exdays*24*60*60*1000));
		let expires = "expires="+ d.toUTCString();
		document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}

	function getCookie(cname) {
		let name = cname + "=";
		let decodedCookie = decodeURIComponent(document.cookie);
		let ca = decodedCookie.split(';');
		for(let i = 0; i <ca.length; i++) {
			let c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	}

	/* xử lý sticky top */

	function observeStickyHeaderChanges(container) {
		observeHeaders(container);
		observeFooters(container);
	}

	observeStickyHeaderChanges(document);

	function observeHeaders(container) {
		const observer = new IntersectionObserver((records, observer) => {
			for (const record of records) {
				const targetInfo = record.boundingClientRect;
				const stickyTarget = record.target.parentElement.querySelector('.sticky-top');
				const rootBoundsInfo = record.rootBounds;

				// Started sticking.
				if (targetInfo.bottom < rootBoundsInfo.top) {
					fireEvent(true, stickyTarget);
				}

				// Stopped sticking.
				if (targetInfo.bottom >= rootBoundsInfo.top &&
				  targetInfo.bottom < rootBoundsInfo.bottom) {
					fireEvent(false, stickyTarget);
				}
			}
		}, {threshold: [0], root: container});

		// Add the top sentinels to each section and attach an observer.
		const sentinels = addSentinels(container, 'sticky_sentinel--top');
		sentinels.forEach(el => observer.observe(el));
	}

	function observeFooters(container) {
		const observer = new IntersectionObserver((records, observer) => {
			for (const record of records) {
				const targetInfo = record.boundingClientRect;
				const stickyTarget = record.target.parentElement.querySelector('.sticky-top');
				const rootBoundsInfo = record.rootBounds;
				const ratio = record.intersectionRatio;

				// Started sticking.
				if (targetInfo.bottom > rootBoundsInfo.top && ratio === 1) {
					fireEvent(true, stickyTarget);
				}

				// Stopped sticking.
				if (targetInfo.top < rootBoundsInfo.top &&
				targetInfo.bottom < rootBoundsInfo.bottom) {
					fireEvent(false, stickyTarget);
				}
			}
		}, {threshold: [1], root: container});

		// Add the bottom sentinels to each section and attach an observer.
		const sentinels = addSentinels(container, 'sticky_sentinel--bottom');
		sentinels.forEach(el => observer.observe(el));
	}

	function addSentinels(container, className) {
		return Array.from(container.querySelectorAll('.sticky-top')).map(el => {
			const sentinel = document.createElement('div');
			sentinel.classList.add('sticky_sentinel', className);
			return el.parentElement.appendChild(sentinel);
		});
	}

	function fireEvent(stuck, target) {
		const e = new CustomEvent('sticky-change', {detail: {stuck, target}});
		document.dispatchEvent(e);
	}

	document.addEventListener('sticky-change', e => {
		const header = e.detail.target;  // header became sticky or stopped sticking.
		const sticking = e.detail.stuck; // true when header is sticky.

		header.classList.toggle('stuck', sticking); // add drop shadow when sticking.

	});

	async function CopyToClipboard(phone_no) {
		try {
			await navigator.clipboard.writeText(phone_no);
			//console.log('Content copied to clipboard');
			return true
		} catch (err) {
			//console.error('Failed to copy: ', err);
			return false;
		}
	}

	jQuery(function($){
		
		$(document).on('click', 'a.copy', function(e){
			e.preventDefault();
			let that = $(this);
			CopyToClipboard(that.next('a').text());
			$('body').find('#'+that.attr('aria-describedby')+' .tooltip-inner').html('Đã sao chép!');
		});

		$('#favorite-remove-button').on('click', function(e){
			e.preventDefault();
			let reload = parseInt($(this).data('reload'));

			$.ajax({
				url:theme.ajax_url+'?action=remove_favorites',
				method:'POST',
				dataType: 'json',
				success: function(res) {
					if(res) {
						if(reload==1) {
							location.reload();
						} else if(reload==0) {
							$('body').find('.fw-shortcode-media-images .list-media .toggle-favorite')
									.data('favorite',0)
									.find('.dashicons')
									.removeClass('dashicons-star-filled')
									.addClass('dashicons-star-empty');
						}
						$('#right-buttons-fixed').addClass('hidden');
					}
				}
			});
		});

	});// jQuery

}); // DOMContentLoaded