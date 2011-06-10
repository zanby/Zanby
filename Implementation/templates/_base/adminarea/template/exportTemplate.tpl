<?xml version="1.0" encoding="UTF-8"?>
<message id="{$mailTemplate.alias}">
        <description>{$mailTemplate.template.description|escape:"html"}</description>
        {foreach key=lKey item=locItem from=$mailTemplate.localizations}
        <locale name="{$lKey}">
        		{if !(empty($locItem.subject) && empty($locItem.body_html) && empty($locItem.body_plain) ) }
                <email>
                        <headers>
                                <subject><![CDATA[{$locItem.subject|replace:']]>':']]]]><![CDATA[>'}]]></subject>
                        </headers>
                        <body>
                                {if empty($locItem.body_plain)}{else}<plain><![CDATA[{$locItem.body_plain|replace:']]>':']]]]><![CDATA[>'}]]></plain>{/if}
                                {if empty($locItem.body_html)}{else}<html><![CDATA[{$locItem.body_html|replace:']]>':']]]]><![CDATA[>'}]]></html>{/if}
                        </body>
                </email>
                {/if}
                {if ! (empty($locItem.pmb_subject) && empty($locItem.pmb_messag)) }
                <pmb>
                        <subject><![CDATA[{$locItem.pmb_subject|replace:']]>':']]]]><![CDATA[>'}]]></subject>
                        <body><![CDATA[{$locItem.pmb_message|replace:']]>':']]]]><![CDATA[>'}]]></body>
                </pmb>
                {/if}
                {if !empty($locItem.attaches) }
                    <attaches>
                    {foreach key=aKey item=attItem from=$locItem.attaches}
                    
                    	<attach name="{$attItem.imageName|escape}">{$attItem.imageSource}</attach>
                    
                    {/foreach}
                    </attaches>
                {/if}
        </locale>
        {/foreach}
</message>
