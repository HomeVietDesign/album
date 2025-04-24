<?php

function first_char($word) {
	return (''!=$word)?mb_substr($word,0,1):'';
}

function debug($var) {
	?>
	<pre><?php print_r($var); ?></pre>
	<?php
}

function debug_log($var) {
	error_log(print_r($var,true));
}

function unyson_exists() {
	return (defined('FW')) ? true : false;
}

function get_term_parents($term, $parents=[]) {
	
	if($term->parent>0) {
		$parent = get_term_by( 'term_id', $term->parent, $term->taxonomy );
		$parents[] = $parent;
		$parents = get_term_parents($parent, $parents);
	}

	return $parents;
}

function get_checkbox_list_terms($terms, $level = 0, $current=0) {
	$list = '';
	if(!empty($terms)) {
		$list .= '<ul class="level-'.$level.'">';
		foreach ($terms as $key => $value) {
			$list .= '<li class="parent-'.$value->parent.(($value->term_id==$current)?' current':'').'">';

			if($level>0) {
				$list .= '<a class="copy" href="javascript:;" data-bs-toggle="tooltip" data-bs-title="Copy"><span class="dashicons dashicons-admin-page"></span></a>';
			}

			$list .= '<label class="term-'.$value->term_id.'"><input type="checkbox" name="cate" '.checked( $value->term_id, $current, false ).' value="'.absint($value->term_id).'">'.esc_html($value->name).'</label>';	

			$list .= get_checkbox_list_terms($value->children, $level+1, $current);
			$list .= '</li>';
		}
		$list .= '</ul>';
	}

	return $list;
}

// lấy danh sách term dạng html ul từ mảng term dạng phân cấp trả về của hàm get_terms_tree
function get_the_list_terms($terms, $media_page='', $level = 0) {
	$list = '';
	if(!empty($terms)) {
		$list .= '<ul class="level-'.$level.'">';
		foreach ($terms as $key => $value) {
			$list .= '<li class="parent-'.$value->parent.'">';

			if($level>0) {
				$list .= '<a class="copy" href="javascript:;" data-bs-toggle="tooltip" data-bs-title="Sao chép"><span class="dashicons dashicons-admin-page"></span></a>';
			}

			//$fbv = absint(get_term_meta( $value->term_id, 'folder', true ));

			$list .= '<a class="term-'.$value->term_id.'" href="'.esc_url(add_query_arg('fc', $value->term_id, $media_page)).'">'.esc_html($value->name).'</a>';	

			$list .= get_the_list_terms($value->children, $media_page, $level+1);
			$list .= '</li>';
		}
		$list .= '</ul>';
	}

	return $list;
}

// lấy mảng term phân cấp dạng phẳng
function get_terms_hierarchical_flat($taxonomy, $parent=0, $hide_empty=false) {
	static $terms_flat = [];

	$terms = get_terms([
		'taxonomy' => $taxonomy,
		'hide_empty' => $hide_empty,
		'parent' => $parent
	]);

	if(is_array($terms) && !empty($terms)) {
		foreach ($terms as $key => $value) {
			$terms_flat[] = $value;
			get_terms_hierarchical_flat( $taxonomy, $value->term_id, $hide_empty=false );
		}
	}

	return $terms_flat;
}

// lấy mảng term dạng phân cấp dạng cây
function get_terms_tree($taxonomy, $parent=0, $hide_empty=false) {
	$terms = get_terms([
		'taxonomy' => $taxonomy,
		'hide_empty' => $hide_empty,
		'parent' => $parent
	]);

	if(is_array($terms) && !empty($terms)) {
		foreach ($terms as $key => $value) {
			$terms[$key]->children = get_terms_tree( $taxonomy, $value->term_id, $hide_empty=false );
		}
	} else {
		$terms = [];
	}

	return $terms;
}

function get_page_by_template($template = '', $one=true) {
  $args = array(
    'meta_key' => '_wp_page_template',
    'meta_value' => $template
  );
  if($one) {
  	$args['number'] = 1;
  }
  $pages = get_pages($args);
  //debug($pages);
  if($one) {
  	return (!empty($pages)) ? $pages[0] : null;
  }
  return $pages; 
}

function wp_format_content($raw_string='') {
	global $wp_embed;
	
	$content = wp_kses_post( $raw_string );

	$content = do_blocks($content);
	$content = wptexturize($content);
	$content = convert_smilies($content);
	$content = convert_chars($content);
	$wp_embed->run_shortcode($content);
	$content = wpautop($content);
	$content = shortcode_unautop($content);
	$content = prepend_attachment($content);
	$content = wp_filter_content_tags($content);
	$content = do_shortcode($content);
	$content = wp_replace_insecure_home_url($content);
	$content = $wp_embed->autoembed($content);

	return $content;
}

function wp_do_shortcode( $tag, array $atts = array(), $content = null ) {
	global $shortcode_tags;

	if ( ! isset( $shortcode_tags[$tag] ) ) {
		return false;
	}

	return call_user_func( $shortcode_tags[$tag], $atts, $content, $tag );
}

function has_role($role, $user=null) {
	if($user==null) $user = wp_get_current_user();
	if($user instanceof WP_User) {
		return in_array($role, (array) $user->roles);
	} else {
		return false;
	}
}