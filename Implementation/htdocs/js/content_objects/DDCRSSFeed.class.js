//-------------------------------------------------------
function changeRSSTitle(elementId, value)
{
    var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
    tmpEl.rssTitle = value;
}
//-------------------------------------------------------
function changeRSSUrl(elementId, value)
{
    var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
    tmpEl.rssUrl = value;
}
//-------------------------------------------------------
function changeRSSView(elementId, value)
{
    var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
    tmpEl.rssView = value;
}
//-------------------------------------------------------
function changeRSSMaxLines(elementId, value)
{
    var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
    tmpEl.rssMaxLines = value;
}
//-------------------------------------------------------


    DDCRSSFeed = function(id, sGroup, config) {
        if (id) {
            this.init(id, sGroup, config);
        }
    };

    YAHOO.extend(DDCRSSFeed, DDC);

    DDCRSSFeed.prototype.getParams = function () {

        var item = this.getGlobalParams();

        item.Data.rss_url       = this.rssUrl;
        item.Data.rss_title     = this.rssTitle;
        item.Data.rss_max_lines = this.rssMaxLines;
        item.Data.rss_view      = this.rssView;

        return item;
    };

    //--------------------------------------------------------------------------------------------
    DDCRSSFeed.prototype.applyEditMode = function() {
        this.rssUrl = document.getElementById('rss_url_'+this.ID).value;
        this.rssMaxLines = document.getElementById('rss_max_lines_'+this.ID).value;
        this.rssTitle = document.getElementById('rss_title_'+this.ID).value;
        this.rssView = document.getElementById('rss_view_'+this.ID).value;

		tinyMCEDeinit('tinyMCE_'+this.ID+'_H');
        //  Headline can be empty in some implementations for specific CO's architecture
        if ( null !== (headline = document.getElementById('tinyMCE_'+this.ID+'_H')) ) {
		    this.headline = headline.value;
        }

        return;
    };
    //--------------------------------------------------------------------------------------------
    DDCRSSFeed.prototype.backupParams = function () {
        this.backupGlobalParams();

        this.bckRssUrl = this.rssUrl;
        this.bckRssTitle = this.rssTitle;
        this.bckRssMaxLines = this.rssMaxLines;
        this.bckRssView = this.rssView;
    };
    //--------------------------------------------------------------------------------------------
    DDCRSSFeed.prototype.restoreParams = function () {
        this.restoreGlobalParams();

        this.rssUrl = this.bckRssUrl;
        this.rssMaxLines = this.bckRssMaxLines;
        this.rssTitle = this.bckRssTitle;
        this.rssView = this.bckRssView;

    };
