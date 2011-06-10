<?php
    function smarty_function_template_document_globalsearch($params, &$smarty)
    {    
		Warecorp::addTranslation('/plugins/function.template_document_globalsearch.php.xml'); 
		$theme = Zend_Registry::get('AppTheme');
        $output  = "<td class='prText5 prTCenter'> <img src='".Warecorp_Document_Item::getImageFileNameByExtension($params['object']->getFileExt(),'big')."'><br />".Warecorp::t('Document')."</td>";
        $output .= "<td><a class='prLink2' href='".$params['object']->entityUrl()."'>".htmlspecialchars(strip_tags($params['object']->entityTitle()))."</a>";
		$output .= "<div>".htmlspecialchars(strip_tags($params['object']->entityDescription()))."</div>";
		$output .= "<div class='prIndentTopSmall'><img src='{$theme->images}/decorators/discussions/arrow.jpg' width='9' height='11' /><a class='prLink3' href='".$params['object']->entityUrl()."' title='".$params['object']->entityUrl()."'>".substr($params['object']->entityUrl(), 0, 50);
		if (strlen($params['object']->entityUrl()) > 50){
		$output .= "...";
		}
		$output .= "</a></div></td>";
		$output .= "<td>".$params['object']->entityAuthor()."</td>";
        $output .= "<td>".Warecorp::user_date_format($params['object']->entityCreationDate())."</td>";
        $output .= "<td>&#160;</td><td>";
        if ( empty($params['user']) || $params['user']->getId() != null )
            $output .= "<a class='prLink3' href='javascript:void(0)' onclick=\"SearchApplication.documentAddToMy('".$params['object']->getId()."', 0); return false;\">";
        else
            $output .= "<a class='prLink3' href='".BASE_URL."/".LOCALE."/users/login/'>";
        $output .= Warecorp::t('Add to My Documents')."</a></td>";
        return $output;
    }
    