{foreach from=$current_hierarchy->getCategoryTree($g.id) item=c}
	{if $c.type == 'category'}
		<div style="padding-left:{$c.level*15-15}px;" class="prIndentBottom">
			<input class="prSmallFormItem prIndentRight" type="text" name="cat_{$c.id}" id="cat_{$c.id}" value="{$c.name|escape:html}" onchange="categoryChangeHandler('{$c.id}', '{$g.id}', '{$current_hierarchy->getId()}')">
			{if $c.level < $maxCategoryDepth+1}
				<a href="#" onclick="xajax_add_category('{$c.id}', '{$g.id}', '{$current_hierarchy->getId()}'); return false;">+ {t}add sublevel{/t}</a>
			{/if}
			{*if $c.level > 2*}
				{if $c.level < $maxCategoryDepth+1}<span>|</span> {/if}<a href="#" onclick="removeCategory('{$c.id}', '{$g.id}', '{$current_hierarchy->getId()}'); return false;">{t}delete{/t}</a>
			{*/if*}
		</div>
	{/if}
{/foreach}
