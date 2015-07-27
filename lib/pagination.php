<?php
//Create the pagination
    
    $pagination = Utils::user_pagination($current_page,$total,$selected,$current_url);
    
    $tpl->setVariable("UL_PAG",$pagination['list']); 
    
    if($total == 0)
    {
            $tpl->setVariable('PSTATS','inactive');
            $tpl->setVariable('PINACTIVE','onclick="return false;"');    
            $tpl->setVariable('NINACTIVE','onclick="return false;"');
            $tpl->setVariable('NSTATS','inactive');
    }
    else 
    {
        if(isset($pagination['prev_link']))
        {
            $tpl->setVariable('PREV_LINK',$pagination['prev_link']);
        }
        else 
        {
            $tpl->setVariable('PSTATS',$pagination['pstats']);
            $tpl->setVariable('PINACTIVE',$pagination['pinactive']);    
        }
        
        if(isset($pagination['next_link']))
        {
            $tpl->setVariable('NEXT_LINK',$pagination['next_link']);
        }
        else 
        {
            $tpl->setVariable('NINACTIVE',$pagination['ninactive']);
            $tpl->setVariable('NSTATS',$pagination['nstats']);
        }
    }
    
    
