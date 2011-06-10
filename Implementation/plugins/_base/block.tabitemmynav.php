<?php
function smarty_block_tabitemmynav($params, $content, &$smarty)
{
    if ( $content !== null ) 
    {
        /*$params['link'] = ( !isset($params['link']) ) ? '#' : $params['link'];
        $onclick = ( isset($params['onclick']) ) ? sprintf(" onclick=\"%s\"",$params['onclick']) : "";
        $params['labelid'] = ( isset($params['labelid']) ) ? ' id="'.$params['labelid'].'"' : '';

        $position = (isset($params['position'])) ? $params['position'] : 'top';

        if ($position == 'top')
        {
            if ( isset($params['active']) && $params['active'] ) 
            {
                $content = '<td class="taba_top_l"></td><td class="taba_top_c"><a href="'.$params['link'].'" class="active"'. $onclick .''.$params['labelid'].'>'.$content.'</a></td><td class="taba_top_r"></td>';
                $_SESSION['tab'] = 'a';
            } 
            else 
            {
                switch ($_SESSION['tab'])
                {
                    case 's':
                        $cl = '<td class="tab_top_l"></td>';
                        break;
                    case 'a':
                        $cl = '';
                        break;
                    case 'u':
                        $cl = '<td class="tab_top_r"></td><td class="tab_top_l"></td>';
                        break;
                }
                $content = $cl.'<td class="tab_top_c"><a href="'.$params['link'].'"'. $onclick .''.$params['labelid'].'>'.$content.'</a></td>';
                $_SESSION['tab'] = 'u';
            }
        } 
        else 
        {
            if ( isset($params['active']) && $params['active'] ) 
            {
                $content = '<td class="taba_bot_l"></td><td class="taba_bot_c"><a href="'.$params['link'].'" class="active"'. $onclick .''.$params['labelid'].'>'.$content.'</a></td><td class="taba_bot_r"></td>';
            } 
            else 
            {
                $content = '<td class="tab_bot_l"></td><td class="tab_bot_c"><a href="'.$params['link'].'"'. $onclick .''.$params['labelid'].'>'.$content.'</a></td><td class="tab_bot_r"></td>';
            }
        }*/
        $content = '<div class="tab_start">
						<div class="tab_center_start">&nbsp;</div>
                        <div class="justify"></div>
                    </div>                                            
                    <div class="tab_active">
                    	<div class="tab_active_left"></div>
                        <div class="tab_active_center"><a href="'.$params['link'].'">'.$content.'</a></div>
                        <div class="tab_active_right"></div>
                        <div class="justify"></div>
					</div>';
        return $content;
    }
}