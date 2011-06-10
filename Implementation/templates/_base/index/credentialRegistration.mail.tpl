{capture name="_subject_"}

{/capture}

{capture name="_mail_text_part_"}
First Name: {$params.firstname}              
Last Name: {$params.lastname}                
Email: {$params.email}                   
Address 1: {$params.address1}            
Address 2: {$params.address2}            
Phone: {$params.phone}                   
Organization: {$params.organization}     
Website: {$params.website}               
Description Plans: {$params.description} 
{if $params.filename}Here is your file: {$params.filename}{/if}        
{/capture}

{capture name="_mail_html_part_"}
First Name: {$params.firstname|escape:html} </br>              
Last Name: {$params.lastname|escape:html} </br>                
Email: {$params.email|escape:html} </br>                   
Address 1: {$params.address1|escape:html} </br>            
Address 2: {$params.address2|escape:html} </br>            
Phone: {$params.phone|escape:html} </br>                   
Organization: {$params.organization|escape:html} </br>     
Website: {$params.website|escape:html} </br>               
Description Plans: {$params.description|escape:html} </br> 
{if $params.filename}Here is your file: {$params.filename|escape:html} </br> {/if}
{/capture}