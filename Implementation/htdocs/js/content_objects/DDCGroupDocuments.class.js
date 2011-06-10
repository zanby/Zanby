DDCGroupDocuments = function(id, sGroup, config) {
    if (id) {
        this.init(id, sGroup, config);
    }
};

YAHOO.extend(DDCGroupDocuments, DDCMyDocuments);

DDCMyDocuments.prototype.applyEditMode = function()
{
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

    xajax_share_my_documents_to_group(this.documents);
    return;
};

