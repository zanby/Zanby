<?php
function smarty_block_contentblock($params, $content, &$smarty)
{
	$theme = Zend_Registry::get('AppTheme');
    if ( $content !== null ) {
        $params['width']        = ( !isset($params['width']) ) ? null : $params['width'];
        $params['height']       = ( !isset($params['height']) ) ? null : $params['height'];

        $out = '<table'.( ($params['width']) ? ' width="'.$params['width'].'"' : '' ).( ($params['height']) ? ' height="'.$params['height'].'"' : '' ).' border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;">';
        $out .= '
            <tr>
                <td width="6" height="6"><img src="{$theme->images}/content/11.gif" alt="" width="6" height="6" /></td>
                <td background="{$theme->images}/content/12.gif"><img src="{$theme->images}/decorators/px.gif" alt="" width="1" height="1" /></td>
                <td width="6"><img src="{$theme->images}/content/13.gif" alt="" width="6" height="6" /></td>
            </tr>
            <tr>
                <td background="{$theme->images}/content/21.gif"><img src="{$theme->images}/decorators/px.gif" alt="" width="1" height="1" /></td>
                <td bgcolor="#FFFFFF" align="left"> 
                    '.$content.'
                </td>
                <td background="{$theme->images}/content/23.gif"><img src="{$theme->images}/decorators/px.gif" alt="" width="1" height="1" /></td>
            </tr>
            <tr>
                <td height="8"><img src="{$theme->images}/content/31.gif" alt="" width="6" height="8" /></td>
                <td background="{$theme->images}/content/32.gif"><img src="{$theme->images}/decorators/px.gif" alt="" width="1" height="1" /></td>
                <td><img src="{$theme->images}/content/33.gif" alt="" width="6" height="8" /></td>
            </tr>
        </table>        
        ';
        return $out;
    }
}