<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

/**
 * @var array $atts
 */
$temp_cat = isset($_GET['cat']) ? absint($_GET['cat']) : 0;

$folder_cat = isset($atts['folder_cat']) ? array_map('absint', $atts['folder_cat']) : [];

$folder_cat_id = (!empty($folder_cat))?$folder_cat[0]:0;

$temp_cat = ($temp_cat==$folder_cat_id)?$temp_cat:0;

$folder = ($folder_cat_id>0) ? absint(get_term_meta($folder_cat_id, 'folder', true)) : 0;

if($folder===0) $folder = -1;

$shortcode_html_id = uniqid('fw-shortcode-folder-images-');

$numbers = intval($atts['numbers']);

$args = [
    'post_type' => 'attachment',
    'posts_per_page' => $numbers,
    'post_status' => 'inherit',
    'fbv' => $folder
    //'post_mime_type' => array( 'image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/tiff', 'image/x-icon' )
];

if($temp_cat) {
    $args['tax_query'] = [
        'folder_cat' => [
            'taxonomy' => 'folder_cat',
            'field' => 'term_id',
            'terms' => $temp_cat
        ]
    ];
} else {
    $args['tax_query'] = [
        'folder_cat' => [
            'taxonomy' => 'folder_cat',
            'field' => 'term_id',
            'terms' => $folder_cat_id,
            'operator' => 'NOT IN'
        ]
    ];
}

//debug($args);

$meta_query = [
    '_rating' => [
        'key' => '_rating',
        'type' => 'NUMERIC'
    ]
];

$args['orderby'] = [
    '_rating' => 'DESC',
    'date' => 'DESC',
    'ID' => 'DESC',
];

$is_favorite = isset($atts['is_favorite'])?boolval($atts['is_favorite']):false;

if(!empty($meta_query)) {
    $args['meta_query'] = $meta_query;
}

if($is_favorite) {
    $favorites = \FW_Shortcode_Folder_Images::get_favorites();
    $args['post__in'] = empty($favorites)?[0]:$favorites;
}

$query = new \WP_Query($args);

//debug($query->request);

$nonce = wp_create_nonce('media_front_end_ajax');
?>
<div id="<?=$shortcode_html_id?>" class="fw-shortcode-folder-images">
    <div class="fw-shortcode-folder-images-inner">
        <input type="hidden" name="query" value="<?=esc_attr(json_encode($query->query))?>">
        <input type="hidden" name="folder" value="<?=$folder?>">
        <input type="hidden" name="folder_cat" value="<?=$folder_cat_id?>">
        <!-- <input type="hidden" name="is_favorite" value="<?=($is_favorite?1:0)?>"> -->
        <input type="hidden" name="uri" value="<?php echo esc_attr($_SERVER['REQUEST_URI']); ?>">
        <div class="fw-shortcode-folder-images-search py-2 sticky-top bg-white">
            <?php
            $folder_obj = null;
            if($folder>0 && class_exists('\FileBird\Model\Folder')) {
                $folder_obj = \FileBird\Model\Folder::findById($folder, '*');
                $parents = \FW_Shortcode_Folder_Images::get_folder_parents($folder_obj, []);

                if(!empty($parents)) {
                   ?>
                   <div class="folder-breadcrumbs d-flex flex-wrap justify-content-start align-items-center px-2">
                       <?php
                       foreach ($parents as $value) {
                           ?>
                           <div><?php echo esc_html($value->name); ?></div><div>/</div>
                           <?php
                       }
                       ?>
                   </div>
                   <?php 
                }
            }
            ?>
            <div class="filter-controls d-flex flex-wrap justify-content-between align-items-center">
                <div class="fs-4 ps-2 d-flex align-items-center">
                    <a href="<?php echo remove_query_arg('cat', fw_current_url()); ?>">
                    <?php
                    if($folder_obj) {
                        echo esc_html($folder_obj->name);
                    } else if($folder_obj==-1) {
                        echo 'Tất cả';
                    } else if($is_favorite) {
                        echo 'Danh sách đã chọn';
                    }
                    ?>
                    </a>
                    <?php
                    if($folder_obj) {
                        ?>
                        <a href="<?=esc_url(admin_url( 'upload.php?folder='.$folder ))?>" class="d-block ms-3 d-flex align-items-center" title="Thư mục tải lên"><span class="dashicons dashicons-cloud-upload" style="width: auto;height: auto;font-size: inherit;"></span></a>
                        <a href="<?php echo add_query_arg('cat', $folder_cat_id, fw_current_url()); ?>" class="mx-3 fs-4 d-flex align-items-center text-primary"><span class="dashicons <?php echo ($temp_cat)?'dashicons-open-folder':'dashicons-category'; ?>" style="width: auto;height: auto;font-size: inherit;"></span></a>
                        <?php
                    }
                    ?>
                </div>

                <div class="d-flex align-items-center">
                    <button type="button" class="selected-delete-button btn btn-sm btn-danger me-2 hide" data-nonce="<?=$nonce?>" title="Xóa file đã chọn"><span class="dashicons dashicons-no"></span></button>
                    <button type="button" class="put-bottom-button btn btn-sm btn-primary me-2 hide" data-nonce="<?=$nonce?>" title="Đẩy xuống cuối">
                        <span class="dashicons dashicons-arrow-down-alt"></span>
                    </button>

                    <?php if($temp_cat) { ?>
                        <button type="button" class="unsave-button btn btn-sm btn-primary me-2 hide" data-nonce="<?=$nonce?>" title="Chuyển ra thư mục chính">
                            <span class="dashicons dashicons-arrow-left-alt"></span>
                        </button>
                    <?php } else { ?>
                        <button type="button" class="save-button btn btn-sm btn-primary me-2 hide" data-nonce="<?=$nonce?>" title="Chuyển vào thư mục tạm">
                            <span class="dashicons dashicons-arrow-right-alt"></span>
                        </button>
                    <?php } ?>

                    <div class="bulk-select-wrap my-2 me-3 d-flex">
                        <label class="btn btn-sm btn-outline-secondary" title="Chọn tất cả"><input type="checkbox" class="bulk-select" style="vertical-align: middle;"></label>
                    </div>

                    <div class="dropdown me-3">
                        <button class="btn btn-sm btn-danger" type="button" data-bs-toggle="dropdown" title="Lưu/Loại danh sách">
                        <span class="dashicons dashicons-star-half"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item toggle-favorite-all toggle-on text-success" href="javascript:;" data-act="on"><span class="dashicons dashicons-star-filled"></span> Lưu vào danh sách</a></li>
                            <li><a class="dropdown-item toggle-favorite-all toggle-off text-warning" href="javascript:;" data-act="off"><span class="dashicons dashicons-star-empty"></span> Loại khỏi danh sách</a></li>
                        </ul>
                    </div>
                    <?php
                    if($query->max_num_pages>1) {
                        ?>
                        <div class="fw-shortcode-folder-images-paginate-links paginate-links d-flex justify-content-center align-items-center">
                            <?php echo \FW_Shortcode_Folder_Images::pagination($query); ?>
                        </div>
                        <?php
                    } // if pagination
                    ?>
                 
                </div>
            </div>
        </div>
        <div class="media-container">
            <?php \FW_Shortcode_Folder_Images::media_images($query); ?>
        </div>
    </div>
</div>
<?php