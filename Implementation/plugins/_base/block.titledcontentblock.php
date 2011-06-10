<?php
function smarty_block_titledcontentblock($params, $content, &$smarty)
{      
    if ( $content !== null ) {
        $params['title']        = ( !isset($params['title']) ) ? "" : $params['title'];
        $smarty->assign("title", $params['title']);
        if (isset($params["nobreak"])){
            $smarty->assign("nobreak", 1);
        }
		if (isset($params["module"])){
			$smarty->assign("module", $params['module']);
			$smarty->assign("disablePrint", $params['disablePrint']);
			$smarty->assign("disableBookmark", $params['disableBookmark']);
			$smarty->assign("disableRss", $params['disableRss']);
			$smarty->assign("disableEmail", $params['disableEmail']);		
		}
		if (isset($params["html"])) {
            $smarty->assign ("html", $params['html']);
		}
		if (isset($params["htmlPosition"])) {
            $smarty->assign ("htmlPosition", strtolower($params["htmlPosition"] == "left") ? "left" : "right");
		}
		if (isset($params['onclick'])) {
            $smarty->assign ("onclick", $params['onclick']);
		}
		if (isset($params["addLink"])){
			$smarty->assign("addLink", $params["addLink"]);
			if(isset($params["addLinkName"])){
				$smarty->assign("addLinkName", $params["addLinkName"]);
			}
			else{
				$smarty->assign("addLinkName", $params["NoName"]);
			}
		}
        $smarty->assign("content", $content);
        $_content = $smarty->fetch("_design/content_blocks/titled_content_block.tpl");
        
        print $_content;
    }
}

?>
