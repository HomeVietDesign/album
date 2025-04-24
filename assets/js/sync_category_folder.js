window.addEventListener('DOMContentLoaded', function(){
	jQuery(function($){
	
		let $sync_wrap = $('#sync-category-folder'),
			$scf_sync = $('#scf_sync'),
			$scf_sync_result = $('#sync-category-folder-result'),
			user = parseInt($sync_wrap.find('input[name="user"]').val()),
			nonce = $sync_wrap.find('input[name="nonce"]').val();

		function do_sync() {
			let sync_direction = $sync_wrap.find('[name="sync_direction"]').val();

			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: {action: 'sync_category_folder', user: user, direction: sync_direction, nonce: nonce},
				beforeSend: function(xhr) {
					$scf_sync.prop('disabled', true);
				},
				success: function(response) {
					if(response) {
						$scf_sync_result.html('Quá trình đồng bộ được xử lý dưới nền.');
					} else {
						$scf_sync_result.html('Quá hạn xử lý, tải lại trang rồi thử lại.');
					}
				},
				error: function(xhr) {
					$scf_sync_result.html('Có lỗi xảy ra, xin thử lại.');
				},
				complete: function(xhr) {
					$scf_sync.prop('disabled', false);
				}
			});
			

		}

		$scf_sync.on('click', function(e){
			$scf_sync_result.html('');
			do_sync();
		});
		
	});
});