<?php
    function smarty_function_template_message_default($params, &$smarty)
    {   
		Warecorp::addTranslation('/plugins/function.template_message_default.php.xml');
        $messageClass="";   
        if ($params['object']->getIsRead() == 0)
        {
            $messageClass = "znBold";  
        }
        
        $mid = $params['object']->getId();
        if ($params['object']->getSenderType() == 1)
        {
            $senderName = $params['object']->getSender()->getLogin();
        }
        else{
            $senderName = $params['object']->getSender()->getName();
        }
        $output  = "<tr class='".$messageClass."'>";
        $output .= "<td style='border: 1px solid black;'> <input type='checkbox' checked='0'></td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('Sender')." - ".$senderName."</td>";
        $output .= "<td style='border: 1px solid black;'><a href='/".LOCALE."/messageview/order/asc/id/".$mid."/'>".$params['object']->getSubject()."</a></td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('Date')." - ".Warecorp::user_date_format($params['object']->getCreateDate())."</td>";
        $output .= "</tr>";
        
        return $output;
    }

    ?>