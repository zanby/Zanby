function addDDDocument(elementId)
{
    tmpElement = WarecorpDDblockApp.getObjByID(elementId);
    tmpElement.documents[tmpElement.documents.length] = 0;
    WarecorpDDblockApp.redrawElementLight(tmpElement.ID);
}

function removeDDDocumentSlot(elementId, document_index)
{
    tmpElement = WarecorpDDblockApp.getObjByID(elementId);

    if (tmpElement.documents.length<=1) {
        return false;
    }

    for(k=document_index;k<(tmpElement.documents.length-1);k++)
    {
        tmpElement.documents[k]=tmpElement.documents[k+1];
    }
    tmp=tmpElement.documents.pop();

    WarecorpDDblockApp.redrawElementLight(tmpElement.ID);

    return false;
}

function removeSingleDDDocumentSlot(elementId, document_index)
{
    tmpElement = WarecorpDDblockApp.getObjByID(elementId);
    tmpElement.documents[tmpElement.documents.length] = 0;

    for(k=document_index;k<(tmpElement.documents.length-1);k++)
    {
        tmpElement.documents[k]=tmpElement.documents[k+1];
    }
    tmp=tmpElement.documents.pop();

    WarecorpDDblockApp.redrawElementLight(tmpElement.ID);

    return false;
}

DDCMyDocuments = function(id, sGroup, config) {
    if (id) {
        this.init(id, sGroup, config);
    }
};

YAHOO.extend(DDCMyDocuments, DDC);

DDCMyDocuments.prototype.getParams = function () {

    var item = this.getGlobalParams();

    item.Data.items = this.documents;

    return item;
};

    //--------------------------------------------------------------------------------------------
    DDCMyDocuments.prototype.backupParams = function () {
        this.backupGlobalParams();

        this.bckDocuments = [];
        for (var i=0; i < this.documents.length; i++) {
            this.bckDocuments[i] = this.documents[i];
        }
    };
    //--------------------------------------------------------------------------------------------
    DDCMyDocuments.prototype.restoreParams = function () {
        this.restoreGlobalParams();

        this.documents = [];
        for (var i=0; i < this.bckDocuments.length; i++) {
            this.documents[i] = this.bckDocuments[i];
        }
    };
      //--------------------------------------------------------------------------------------------
    DDCMyDocuments.prototype.applyEditMode = function(){

        this.tmpDocuments = [];
        for (var i=0; i < this.documents.length; i++) {

            if (this.documents[i]) {
                this.tmpDocuments[this.tmpDocuments.length] = this.documents[i];
            }
        }
        this.documents = this.tmpDocuments;

        if ( null !== document.getElementById('tinyMCE_'+this.ID+'_H') ) {
            tinyMCEDeinit('tinyMCE_'+this.ID+'_H');
            this.headline = document.getElementById('tinyMCE_'+this.ID+'_H').value;
        }
        return;
    };
    //--------------------------------------------------------------------------------------------
