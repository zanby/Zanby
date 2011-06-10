    <div style="padding:0 20px 20px 20px">
        <div style='color:#CB0000'>{$error->getMessage()}</div>
        <div><b>{t}File :{/t}</b> {$error->getFile()}</div>
        <div><b>{t}Line :{/t}</b> {$error->getLine()}</div>
        
        <div style="padding-top: 20px; padding-bottom:10px;"><b>{t}REQUEST : {/t}</b></div>
        <div style="width:100%; overflow:auto; max-height:300px; border:1px solid #CFD4D6; padding:5px;">
        {$request}
        </div>
        
        <div style="padding-top: 20px; padding-bottom:10px;"><b>{t}SESSION : {/t}</b></div>
        <div style="width:100%; overflow:auto; max-height:300px; border:1px solid #CFD4D6; padding:5px;">
        {$session}
        </div>
                
        <div style="padding-top: 20px; padding-bottom:10px;"><b>{t}COOKIE : {/t}</b></div>
        <div style="width:100%; overflow:auto; max-height:300px; border:1px solid #CFD4D6; padding:5px;">
        {$cookie}
        </div>

        <div style="padding-top: 20px; padding-bottom:10px;"><b>{t}SERVER : {/t}</b></div>
        <div style="width:100%; overflow:auto; max-height:300px; border:1px solid #CFD4D6; padding:5px;">
        {$server}
        </div>
        
        <div style="padding-top: 20px; padding-bottom:10px;"><b>{t}BACKTRACE : {/t}</b></div>
        <div style="width:100%; overflow:auto; max-height:300px; border:1px solid #CFD4D6; padding:5px;">
        {$backtrace}
        </div>
    </div>

