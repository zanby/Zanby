<?php
function smarty_block_Widget_GlobalSearch($params, $content, &$smarty)
{
	Warecorp::addTranslation('/plugins/block.Widget_GlobalSearch.php.xml');
	$topTags = ( !empty($params['tags']) && is_array($params['tags'])) ? $params['tags'] : array();
	$objUser = $objCurrentUser = $objGroup = null;
	$context = Warecorp::isContext($smarty, $objUser, $objCurrentUser, $objGroup);

    if ( $content !== null ) {
        $output = '';
		if ( $context == 'search' ) {
			if (Warecorp::$actionName == 'search') {
			};

			if (Warecorp::$actionName == 'groups') {
				if ( !empty($topTags)) {
					$output = "<h3>".Warecorp::t("Top Group Tags")."</h3>";   
					$output .= "<div>";      
					foreach ($topTags as $k=>$m){
						$output .="<a class='prTag".$m."' href='".BASE_URL."/".LOCALE."/search/groups/preset/new/keywords/".$k."/'>".substr($k, 0, 15)."</a>";
						$output .=" ";
					}
					$output .="</div>";
				}
			};

			if (Warecorp::$actionName == 'members') {
				if ( !empty($topTags)) {
					$output = "<h3>".Warecorp::t("Top Member Tags")."</h3>";   
					$output .= "<div>";      
					foreach ($topTags as $k=>$m){
						$output .="<a class='prTag".$m."' href='".BASE_URL."/".LOCALE."/search/members/preset/new/keywords/".$k."/'>".substr($k, 0, 15)."</a>";
						$output .=" ";
					}
					$output .="</div>";
				}
			};

			if (Warecorp::$actionName == 'photos') {
				if ( !empty($topTags)) {
					$output = "<h3>".Warecorp::t("Top Photos Tags")."</h3>";   
					$output .= "<div>";      
					foreach ($topTags as $k=>$m){
						$output .="<a class='prTag".$m."' href='".BASE_URL."/".LOCALE."/search/photos/preset/new/keywords/".$k."/'>".substr($k, 0, 15)."</a>";
						$output .=" ";
					}
					$output .="</div>";
				}
			};

			if (Warecorp::$actionName == 'videos') {
                if ( !empty($topTags)) {
                    $output = "<h3>".Warecorp::t("Top Video Tags")."</h3>";   
                    $output .= "<div>";      
                    foreach ($topTags as $k=>$m){
                        $output .="<a class='prTag".$m."' href='".BASE_URL."/".LOCALE."/search/videos/preset/new/keywords/".$k."/'>".substr($k, 0, 15)."</a>";
                        $output .=" ";
                    }
                    $output .="</div>";
                }
			};

			if (Warecorp::$actionName == 'discussions') {

			};

			if (Warecorp::$actionName == 'events') {
			    if ( !empty($topTags)) {
                    $output = "<h3>".Warecorp::t("Top Events Tags")."</h3>";   
                    $output .= "<div>";      
                    foreach ($topTags as $k=>$m){
                        $output .="<a class='prTag".$m."' href='".BASE_URL."/".LOCALE."/search/events/preset/new/keywords/".$k."/'>".substr($k, 0, 15)."</a>";
                        $output .=" ";
                    }
                    $output .="</div>";
                }
			};

			if (Warecorp::$actionName == 'lists') {
                 if ( !empty($topTags)) {
                    $output = "<h3>".Warecorp::t("Top Lists Tags")."</h3>";   
                    $output .= "<div>";      
                    foreach ($topTags as $k=>$m){
                        $output .="<a class='prTag".$m."' href='".BASE_URL."/".LOCALE."/search/lists/preset/new/keywords/".$k."/'>".substr($k, 0, 15)."</a>";
                        $output .=" ";
                    }
                    $output .="</div>";
                }

			};

			if (Warecorp::$actionName == 'documents') {
                if ( !empty($topTags)) {
                    $output = "<h3>".Warecorp::t("Top Documents Tags")."</h3>";   
                    $output .= "<div>";      
                    foreach ($topTags as $k=>$m){
                        $output .="<a class='prTag".$m."' href='".BASE_URL."/".LOCALE."/search/documents/preset/new/keywords/".$k."/'>".substr($k, 0, 15)."</a>";
                        $output .=" ";
                    }
                    $output .="</div>";
                }
			};
		} elseif ($context == 'group_index') {
            if ( !empty($topTags)) {
                $output = "";
                foreach ($topTags as $k=>$m){
                    $output .="<a class='prTag".$m."' href='".BASE_URL."/".LOCALE."/search/groups/preset/new/keywords/".htmlspecialchars($k)."/'>".substr(htmlspecialchars($k), 0, 30)."</a>";
                    $output .=" ";
                }
            }
        } elseif ($context == 'users_index') {
            if ( !empty($topTags)) {
                $output = "";
                foreach ($topTags as $k=>$m){
                    $output .="<a class='prTag".$m."' href='".BASE_URL."/".LOCALE."/search/members/preset/new/keywords/".htmlspecialchars($k)."/'>".substr(htmlspecialchars($k), 0, 30)."</a>";
                    $output .=" ";
                }
            }
        }
        return $output.$content;
    }
}