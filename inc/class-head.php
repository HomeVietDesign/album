<?php
namespace Album;

class Head {

	use \Album\Singleton;

	private function __construct() {
		add_action('wp_head', [$this, 'head_scripts'], 50);
	}

	public static function head_scripts() {
		
		$footer_bg_color = fw_get_db_settings_option('footer_bg_color', '#000');
		$footer_color = fw_get_db_settings_option('footer_color', '#fff');
		if($footer_bg_color) {
			?>
			<style type="text/css">
			#site-footer {
				background-color: <?=$footer_bg_color?>;
				color: <?=$footer_color?>;
			}
			</style>
			<?php
		}

		?>
		<style type="text/css">
			.grecaptcha-badge {
				right: -999999px!important;
			}
		</style>
		<script type="text/javascript">
			function debounce(func, wait = 250) { // Default wait time of 250ms
				let timeout;
				return function executedFunction(...args) {
					clearTimeout(timeout); // Clear any previous timeout
					timeout = setTimeout(() => func.apply(this, args), wait);
				};
			}

			function throttle(func, wait = 250) {
				let isWaiting = false;
				return function executedFunction(...args) {
					if (!isWaiting) {
						func.apply(this, args);
						isWaiting = true;
						setTimeout(() => {
							isWaiting = false;
						}, wait);
					}
				};
			}

			function lazyLoadImages(container) {
				var lazyloadImages;
				//console.log('lazyLoadImages');
				if ("IntersectionObserver" in window) {
					lazyloadImages = container.querySelectorAll("img.lazy");
					var imageObserver = new IntersectionObserver(function(entries, observer) {
						entries.forEach(function(entry) {
							if (entry.isIntersecting) {
								var image = entry.target;
								image.src = image.dataset.src;
								image.srcset = image.dataset.srcset;
								image.sizes = image.dataset.sizes;
								image.classList.remove("lazy");
								imageObserver.unobserve(image);
							}
						});
					});

					lazyloadImages.forEach(function(image) {
						imageObserver.observe(image);
					});
				} else {  
					var lazyloadThrottleTimeout;
					lazyloadImages = container.querySelectorAll("img.lazy");

					function lazyload () {
						if(lazyloadThrottleTimeout) {
							clearTimeout(lazyloadThrottleTimeout);
						}

						lazyloadThrottleTimeout = setTimeout(function() {
							var scrollTop = window.pageYOffset;
							lazyloadImages.forEach(function(img) {
								if(img.offsetTop < (window.innerHeight + scrollTop)) {
									img.src = img.dataset.src;
									img.srcset = img.dataset.srcset;
									img.sizes = img.dataset.sizes;
									img.classList.remove('lazy');
								}
							});
							if(lazyloadImages.length == 0) { 
								document.removeEventListener("scroll", lazyload);
								window.removeEventListener("resize", lazyload);
								window.removeEventListener("orientationChange", lazyload);
							}
						}, 20);
					}

					document.addEventListener("scroll", lazyload);
					window.addEventListener("resize", lazyload);
					window.addEventListener("orientationChange", lazyload);
				}
			}

			window.addEventListener('DOMContentLoaded', function(){
				const root = document.querySelector(':root');
				root.style.setProperty('--site-header--height', document.getElementById('site-header').clientHeight+'px');
				window.addEventListener('resize', function(){
					root.style.setProperty('--site-header--height', document.getElementById('site-header').clientHeight+'px');
				});
			});
		</script>
		<?php
	}

}

Head::get_instance();