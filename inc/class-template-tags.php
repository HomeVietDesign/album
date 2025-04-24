<?php
namespace Album;

final class Template_Tags {


	public static function pagination($query) {
		/*
		max_num_pages : number pages
		query_vars[paged] : current page
		*/
		//debug($query);

		$output = '';
		$paged = ($query->query_vars['paged']==0)?1:$query->query_vars['paged'];

		if($query->max_num_pages>1) {

			// hiển thị về đầu và về trang trước
			if($paged == 1) 
				$output = $output . '<span class="disabled mx-1 d-block p-1" data-paged="1"><span class="dashicons dashicons-controls-skipback"></span></span><span class="disabled mx-1 d-block p-1"><span class="dashicons dashicons-controls-back"></span></span>';
			else	
				$output = $output . '<a class="link mx-1 d-block p-1" href="javascript:void(0);" data-paged="' . 1 . '" ><span class="dashicons dashicons-controls-skipback"></span></a><a class="link mx-1 d-block p-1" href="javascript:void(0);" data-paged="' . ($paged-1) . '" ><span class="dashicons dashicons-controls-back"></span></a>';
			
			$delta = 7; // thay đổi để tăng/giảm số lượng page hiển thị 2 bên trang hiện tại
            $side = $delta+1;
            
            if(($paged-$side)>0) { // -1
                $output = $output . '<a class="link mx-1 d-block p-1" href="javascript:void(0);" data-paged="1" >1</a>';
            }

            if(($paged-$side)>1) {
                    $output = $output . '...';
            }
            
            for($i=($paged-$delta); $i<=($paged+$delta); $i++)    {
                if($i<1) continue;
                if($i>$query->max_num_pages) break;
                if($paged == $i)
                    $output = $output . '<span class="current mx-1 d-block p-1" data-paged="'.$paged.'">'.$i.'</span>';
                else                
                    $output = $output . '<a class="link mx-1 d-block p-1" href="javascript:void(0);" data-paged="' . $i . '" >'.$i.'</a>';
            }
            
            if(($query->max_num_pages-($paged+$delta))>1) {
                $output = $output . '...';
            }

            if(($query->max_num_pages-($paged+$side))>0) {
                if($paged == $query->max_num_pages)
                    $output = $output . '<span class="current mx-1 d-block p-1" data-paged="'.$paged.'">' . $query->max_num_pages .'</span>';
                else                
                    $output = $output . '<a class="link mx-1 d-block p-1" href="javascript:void(0);" data-paged="' . $query->max_num_pages .'" >' . $query->max_num_pages .'</a>';
            }
			
			// hiển thị tiếp theo và cuối cùng
			if($paged < $query->max_num_pages)
				$output = $output . '<a  class="link mx-1 d-block p-1" href="javascript:void(0);" data-paged="' . ($paged+1) . '" ><span class="dashicons dashicons-controls-forward"></span></a><a  class="link mx-1 d-block p-1" href="javascript:void(0);" data-paged="' . $query->max_num_pages . '" ><span class="dashicons dashicons-controls-skipforward"></span></a>';
			else				
				$output = $output . '<span class="disabled mx-1 d-block p-1"><span class="dashicons dashicons-controls-forward"></span></span><span class="disabled mx-1 d-block p-1"><span class="dashicons dashicons-controls-skipforward"></span></span>';
			
			
		}


		return $output;
		
	}

	
}