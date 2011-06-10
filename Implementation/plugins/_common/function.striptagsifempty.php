<?php
    function smarty_function_striptagsifempty($params, &$smarty)
    {
        $_content = empty($params['content'])?'':$params['content'];
        $_container = empty($params['container'])?'':$params['container'];
        $_class = empty($params['class'])?'':$params['class'];
        
        $_scontent = strip_tags($_content);
        if (empty($_content) || empty($_scontent)) return ;
        
        
        if (!empty($_container)){
          if(!empty($_class)){
            $_content = '<'.$_container.' class="'.$_class.'">'.$_content.'</'.$_container.'>';
          } else {
            $_content = '<'.$_container.'>'.$_content.'</'.$_container.'>';
          }
        }
        
        return $_content;
    }
?>