YAHOO.widget.DS_XAJAX = function()
{
    this._init();
};
YAHOO.widget.DS_XAJAX.prototype = new YAHOO.widget.DataSource();
YAHOO.widget.DS_XAJAX.prototype.doQuery = function(oCallbackFn, sQuery, oParent)
{
    doQueryXajax(oCallbackFn, oParent, sQuery);
    return;
}