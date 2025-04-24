document.addEventListener('DOMContentLoaded', function(e){
	jQuery(function($){

		$('.fw-shortcode-folder-images .list-media').imagesLoaded(function(){
			$('.fw-shortcode-folder-images .list-media').masonry();
			$('.fw-shortcode-folder-images .list-media').masonry('layout');
			$('.fw-shortcode-folder-images .list-media').masonry('on','layoutComplete', function( items ){
				var lightbox = new PhotoSwipeLightbox({
					gallery: '.pswp-gallery',
					children: 'a',
					pswpModule: PhotoSwipe 
				});
				lightbox.init();
				lazyLoadImages(this.element);
			});
		});

		function updateChecked($section) {
			let selected = $section.find('input.medias_select:checked'),
				$selected_delete_button = $section.find('.selected-delete-button'),
				$put_bottom_button = $section.find('.put-bottom-button'),
				$save_button = $section.find('.save-button'),
				$unsave_button = $section.find('.unsave-button'),
				medias_selected = [];
			
			if(selected.length>0) {
				$selected_delete_button.removeClass('hide');
				$put_bottom_button.removeClass('hide');
				$save_button.removeClass('hide');
				$unsave_button.removeClass('hide');
				for (var i = 0; i < selected.length; i++) {
					medias_selected.push(selected[i].value);
				}
			} else {
				$selected_delete_button.addClass('hide');
				$put_bottom_button.addClass('hide');
				$save_button.addClass('hide');
				$unsave_button.addClass('hide');
			}

			return medias_selected;
		}

		function load_images($section, paged=1, scrolltop=true) {

			let $result_el = $section.find('.media-container');
		
			let $msr_el = $section.find('.list-media');
			
			let $pagination_links_el = $section.find('.paginate-links');

			let $bulk_select_el = $section.find('.bulk-select');

			let query = JSON.parse($section.find('[name=query]').val());

			$.ajax({
				url:theme.ajax_url+'?action=folder_images_paginate',
				method:'GET',
				data:{query:query, paged:paged},
				beforeSend:function(){
					$result_el.append('<div class="overlay d-flex align-items-center justify-content-center"><div class="loading-text text-white">Đang tải...</div></div>');
					if(scrolltop) {
						$('html,body').scrollTop($section.offset().top-($pagination_links_el.parent().outerHeight()+12));
					}
				},
				success:function(response){
					//console.log(response);
					$result_el.find('.overlay').remove();
					
					let $items = $.parseHTML(response['items']);
					
					
					$msr_el.html($items);

					$msr_el.imagesLoaded(function(){
						$msr_el.masonry('reloadItems');
						$msr_el.masonry('layout');
					});
					
					$pagination_links_el.html(response['paginate_links']);

					$bulk_select_el.prop('checked', false);
					
					updateChecked($section);

				}
			});
		}

		$(document).on('click', '.fw-shortcode-folder-images-paginate-links button.page-numbers', function(e){
			let $this = $(this),
				$section = $this.closest('.fw-shortcode-folder-images'),
				paged = parseInt($this.data('paged'));

			$this.prop('disabled', true);
			load_images($section, paged);
		});

		function calcVideoWrap() {
			$('.list-media').find('.video-wrap').each(function(index, el){
				let $this = $(this),
					w = $this.closest('.inner').width(),
					ratio = $this.data('ratio');
				$this.width(w);
				$this.height(w*ratio);
			});
		}

		$(window).on('resize', throttle(function(e){
			calcVideoWrap();
		},100)).resize();

		function checkFavorite () {
			$.ajax({
				url:theme.ajax_url+'?action=folder_images_check_favorite',
				method:'GET',
				dataType: 'json',
				success: function(res) {
					if(res) {
						$('#right-buttons-fixed').removeClass('hidden');
					} else {
						$('#right-buttons-fixed').addClass('hidden');
					}
				}
			});
		}

		$(document).on('click', '.fw-shortcode-folder-images .toggle-favorite', function(e) {
			let $btn = $(this),
				$section = $btn.closest('.fw-shortcode-folder-image'),
				uri = $section.find('[name=uri]').val(),
				attachment = $btn.data('attachment'),
				favorite = $btn.data('favorite');
			$.ajax({
				url:theme.ajax_url+'?action=folder_images_toggle_favorite',
				method:'POST',
				dataType: 'json',
				data:{attachment:attachment, favorite:favorite, uri:uri},
				beforeSend:function(){
					$btn.prop('disabled', true);
				},
				success:function(response){
					$btn.data('favorite', (response)?'1':'0');
					if(response) {
						$btn.attr('title', 'Bỏ chọn').find('.dashicons')
							.removeClass('dashicons-star-empty')
							.addClass('dashicons-star-filled');
					} else {
						$btn.attr('title', 'Lựa chọn').find('.dashicons')
							.removeClass('dashicons-star-filled')
							.addClass('dashicons-star-empty');
					}

					checkFavorite();
					
				},
				complete: function() {
					$btn.prop('disabled', false);
				}
			});
		});

		$(document).on('change', '.fw-shortcode-folder-images input.medias_select', function(e){
			let $this = $(this),
				$section = $this.closest('.fw-shortcode-folder-images'),
				$bulk_select_el = $section.find('.bulk-select');
			
			$bulk_select_el.prop('checked', false);
			
			updateChecked($section);
		});

		$(document).on('change', '.fw-shortcode-folder-images .bulk-select', function(e){
			let $bulk = $(this),
				$section = $bulk.closest('.fw-shortcode-folder-images');
			
			if($bulk.prop('checked')) {
				$section.find('input.medias_select').prop('checked', true);
			} else {
				$section.find('input.medias_select').prop('checked', false);
			}

			updateChecked($section);
		});

		$(document).on('click', '.fw-shortcode-folder-images a.toggle-favorite-all', function(e){
			let $btn = $(this),
				$section = $btn.closest('.fw-shortcode-folder-images'),
				uri = $section.find('[name=uri]').val(),
				attachments = [],
				items = $section.find('.list-media .item'),
				act = $btn.data('act');
			if(items.length>0) {
				for (var i = 0; i < items.length; i++) {
					attachments.push(parseInt($(items[i]).find('input.medias_select').val()));
				}

				$.ajax({
					url:theme.ajax_url+'?action=folder_images_toggle_favorite_all',
					method:'POST',
					dataType: 'json',
					data:{attachments:attachments, act:act, uri:uri},
					beforeSend:function(){
						$btn.addClass('disabled');
						for (var i = 0; i < attachments.length; i++) {
							$('.toggle-favorite-'+attachments[i]).prop('disabled', true);
						}
					},
					success:function(response){
						if(response.code==1) {
							if(act=='on') {
								for (var i = 0; i < attachments.length; i++) {
									$('.toggle-favorite-'+attachments[i]).attr('title', 'Bỏ chọn').find('.dashicons')
										.removeClass('dashicons-star-empty')
										.addClass('dashicons-star-filled');
								}
							} else {
								for (var i = 0; i < attachments.length; i++) {
									$('.toggle-favorite-'+attachments[i]).attr('title', 'Lựa chọn').find('.dashicons')
										.removeClass('dashicons-star-filled')
										.addClass('dashicons-star-empty');
								}
							}
						}

						checkFavorite();
					},
					complete: function() {
						$btn.removeClass('disabled');
						for (var i = 0; i < attachments.length; i++) {
							$('.toggle-favorite-'+attachments[i]).prop('disabled', false);
						}
					}
				});
			}
		});

		$(document).on('click', '.fw-shortcode-folder-images .selected-delete-button', function(e){
			let $btn = $(this),
				$section = $btn.closest('.fw-shortcode-folder-images'),
				uri = $section.find('[name=uri]').val(),
				paged = parseInt($section.find('.paginate-links .current').data('paged')),
				medias_selected = updateChecked($section);

			$btn.prop('disabled', true);
			
			if(medias_selected.length>0) {
				if(confirm("Xác nhận xóa?")) {
					$.ajax({
						url: theme.ajax_url,
						type: 'POST',
						dataType: 'json',
						data: {action: 'folder_images_delete_medias_selected', items: medias_selected, uri: uri, nonce: $btn.data('nonce')},
						beforeSend: function(xhr) {
							
						},
						success: function(response) {
							if(response.code===1) {
								load_images($section, paged, false);
							} else {
								alert(response.msg);
							}

						},
						error: function() {
							alert(response.msg);
						},
						complete: function() {
							$btn.prop('disabled', false);
						}
					});
				}
			}
		});

		$(document).on('click', '.fw-shortcode-folder-images .put-bottom-button', function(e){
			let $btn = $(this),
				$section = $btn.closest('.fw-shortcode-folder-images'),
				uri = $section.find('[name=uri]').val(),
				paged = parseInt($section.find('.paginate-links .current').data('paged')),
				folder = parseInt($section.find('[name=folder]').val()),
				medias_selected = updateChecked($section);
			
			$btn.prop('disabled', true);

			$.ajax({
				url: theme.ajax_url,
				type: 'POST',
				dataType: 'json',
				data: {action: 'folder_images_put_bottom_medias_selected', items: medias_selected, folder: folder, uri: uri, nonce: $btn.data('nonce')},
				beforeSend: function(xhr) {
					
				},
				success: function(response) {
					if(response.code===1) {
						load_images($section, paged, false);
					} else {
						alert(response.msg);
					}
				},
				complete: function() {
					$btn.prop('disabled', false);
				}
			});

		});

		$(document).on('click', '.fw-shortcode-folder-images .save-button', function(e){
			let $btn = $(this),
				$section = $btn.closest('.fw-shortcode-folder-images'),
				uri = $section.find('[name=uri]').val(),
				paged = parseInt($section.find('.paginate-links .current').data('paged')),
				folder = parseInt($section.find('[name=folder]').val()),
				folder_cat = parseInt($section.find('[name=folder_cat]').val()),
				medias_selected = updateChecked($section);
			
			$btn.prop('disabled', true);

			$.ajax({
				url: theme.ajax_url,
				type: 'POST',
				dataType: 'json',
				data: {action: 'folder_images_save_medias_selected', items: medias_selected, uri: uri, folder: folder, folder_cat: folder_cat, nonce: $btn.data('nonce')},
				beforeSend: function(xhr) {
					
				},
				success: function(response) {
					if(response.code===1) {
						load_images($section, paged, false);
					} else {
						alert(response.msg);
					}
				},
				complete: function() {
					$btn.prop('disabled', false);
				}
			});

		});

		$(document).on('click', '.fw-shortcode-folder-images .unsave-button', function(e){
			let $btn = $(this),
				$section = $btn.closest('.fw-shortcode-folder-images'),
				uri = $section.find('[name=uri]').val(),
				paged = parseInt($section.find('.paginate-links .current').data('paged')),
				folder = parseInt($section.find('[name=folder]').val()),
				folder_cat = parseInt($section.find('[name=folder_cat]').val()),
				medias_selected = updateChecked($section);
			
			$btn.prop('disabled', true);

			$.ajax({
				url: theme.ajax_url,
				type: 'POST',
				dataType: 'json',
				data: {action: 'folder_images_unsave_medias_selected', items: medias_selected, uri: uri, folder: folder, folder_cat: folder_cat, nonce: $btn.data('nonce')},
				beforeSend: function(xhr) {
					
				},
				success: function(response) {
					if(response.code===1) {
						load_images($section, paged, false);
					} else {
						alert(response.msg);
					}
				},
				complete: function() {
					$btn.prop('disabled', false);
				}
			});

		});

	});
});