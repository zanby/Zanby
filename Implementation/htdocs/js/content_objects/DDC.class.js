
    YAHOO.util.DD.prototype.onTop = 0;

    DDC = function(id, sGroup, config) {
        if (id) {
            this.init(id, sGroup, config);
        }
    };
    YAHOO.extend(DDC, YAHOO.util.DD);


    DDC.prototype.getGlobalParams = function () {

        var item = [];

        item.ID                     = this.id;
        item.targetID               = this.targetID;
        item.ContentType            = this.contentType;
        item.positionHorizontal     = this.positionHorizontal;
        item.positionVertical       = this.positionVertical;

        item.Style                  = [];
        item.Style.backgroundColor  = this.backgroundColor;
        item.Style.borderColor   	= this.borderColor;
        item.Style.borderStyle      = this.borderStyle;

        item.Data           = [];
        item.Data.headline  = this.headline;

        return item;
    };


    DDC.prototype.backupGlobalParams = function () {
        this.bckBackgroundColor = this.backgroundColor;
        this.bckBorderColor = this.borderColor;
        this.bckBorderStyle = this.borderStyle;

        this.bckHeadline = this.headline;

        return;
    };

    DDC.prototype.restoreGlobalParams = function () {
        this.backgroundColor = this.bckBackgroundColor;
        this.borderColor = this.bckBorderColor;
        this.borderStyle = this.bckBorderStyle;
        //WarecorpDDblockApp.refreshStyles(this.ID);

        this.headline = this.bckHeadline;

        return;
    };
    DDC.prototype.load = function (NewElID, item) {
    };
//--------------------------------------------------------------------------------------------
    DDC.prototype.setEditMode = function(){
        return;
    };
    DDC.prototype.resetEditMode = function(){
        tinyMCEDeinit('tinyMCE_'+this.ID+'_H');
        return;
    };
    DDC.prototype.cancelEditMode = function(){
        tinyMCEDeinit('tinyMCE_'+this.ID+'_H');
        return;
    };
    DDC.prototype.applyEditMode = function(){
		tinyMCEDeinit('tinyMCE_'+this.ID+'_H');
        //  Headline can be empty in some implementations for specific CO's architecture
        if ( null !== (headline = document.getElementById('tinyMCE_'+this.ID+'_H')) ) {
            this.headline = headline.value;
        }
        return;
    };
//--------------------------------------------------------------------------------------------
    DDC.prototype.setBackgroundColor = function(backgroundColor) {
        this.backgroundColor = backgroundColor;
        return;
    };

    DDC.prototype.setBorderColor = function(borderColor) {
        this.borderColor = borderColor;
        return;
    };

    DDC.prototype.setBorderStyle = function(borderStyle) {
        this.borderStyle = borderStyle;
        return;
    };
    DDC.prototype.getBackgroundColor = function() {
        return this.backgroundColor;
    };

    DDC.prototype.getBorderColor = function() {
        return this.borderColor;
    };

    DDC.prototype.getBorderStyle = function() {
        return this.borderStyle;
    };

    DDC.prototype.resetStyle = function() {
        this.backgroundColor = '';
        this.borderColor = '';
        this.borderStyle = '';
        return;
    };
//--------------------------------------------------------------------------------------------
    DDC.prototype.changeTarget = function(targetId){
        return;
    };

    DDC.prototype.setHeadline = function(sHeadline){
        this.headline = sHeadline;
        return;
    };
