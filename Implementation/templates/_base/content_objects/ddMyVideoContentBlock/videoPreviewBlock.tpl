<img width="120" id="a_image_preview_ddMyVideoContentBlock" src="{$currentImage->getCover()->setWidth(120)->setHeight(90)->getImage()}"  name="{$currentImage->getId()}" />
{if $currentImage->getId()}
    <br />
    {if $currentImage->getSource() == 'own'}
    	{t}Filename:{/t} {$currentImage->getFilename()}<br />
        {t}Filesize:{/t} {$currentImage->getSizeAsString()}<br />
        {t}Length:{/t} {$currentImage->getLengthAsString()}<br />
    {/if}
    
    {if $currentImage->getSource() == 'own'}{t}Uploaded{/t}{else}{t}Embedded{/t}{/if}
    {t}by{/t} {$currentImage->getCreator()->getLogin()|escape:html}<br />
    {t}on{/t} {$currentImage->getCreateDate()|user_date_format:$user->getTimezone()}<br />
    {if $currentImage->getGallery()->isShared($currentImage->getGallery()->getOwner())}{t}Status: Shared{/t}<br />{/if}
    {*if $currentImage->getGallery()->isPublished()}{t}Status: Published{/t}<br />{/if*}
{/if}