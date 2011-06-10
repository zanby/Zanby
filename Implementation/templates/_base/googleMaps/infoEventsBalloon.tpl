{strip}
{if $level eq 'continent'}
    <h2 class="znTColor17">{$continentName}</h2>
    <div>{t}Number of Events:{/t} {$countEvents}</div>
{elseif $level eq 'country'}
    <h2 class="znTColor17">{$countryName}</h2>
    <div>{t}Number of Events:{/t} {$countEvents}</div>
{/if}
{/strip}