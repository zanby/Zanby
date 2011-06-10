{tab template="tabs1" active=$active}
    {tabitem link="#null" onclick="xajax_chooseSavedVenue(); return false;" name="add"}{t}Add venue{/t}{/tabitem} 
    {tabitem link="#null" name="saved" onclick="xajax_loadSavedVenues(getSearches()); return false;"}{t}Saved Venues{/t}{/tabitem}
    {tabitem link="#null" onclick="xajax_findaVenue(); return false;" name="find" last="last"}{t}Find a venue{/t}{/tabitem}
{/tab}