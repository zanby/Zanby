{tab template="tabs1" active=$active}
	{tabitem link="#" name="add" onclick="xajax_chooseSavedWWVenue(); return false;"}{t}Worldwide venues{/t}{/tabitem}
	{tabitem link="#" name="saved"  onclick="xajax_loadSavedWWVenues(getWWSearches()); return false;" last="last"}{t}My Saved Worldwide Venues{/t}{/tabitem}
{/tab}