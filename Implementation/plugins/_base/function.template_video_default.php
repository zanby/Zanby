<?php
    function smarty_function_template_video_default($params, &$smarty)
    {  
		Warecorp::addTranslation('/plugins/function.template_video_default.php.xml');    
        $output = "<td style='border: 1px solid black;'><img src='".$params['object']->entityPicture()->setWidth(50)->setHeight(50)->getImage($user)."' /></td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('title')." - ".htmlspecialchars(strip_tags($params['object']->getTitle()))."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('Decription')." - ".htmlspecialchars(strip_tags($params['object']->getDescription()))."</td>";
        return $output;
    }
    
?>