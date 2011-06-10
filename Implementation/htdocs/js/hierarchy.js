
    var tree;
    var TreeChilds;
    var treeCollection = new Array();
    
    /**
    *
    *
    */
    targetMouseOver = function (obj) {
        if ( YAHOO.util.DragDropMgr.dragCurrent != null ) {
            if ( YAHOO.util.DragDropMgr.dragCurrent != null ) {
                YAHOO.util.Dom.get(YAHOO.util.DragDropMgr.dragCurrent.getDragEl().id).className = "groupDDo";
            }
            YAHOO.util.Dom.setStyle(obj, "background-color", "#fff");
            YAHOO.util.Dom.setStyle(obj, "border", "1px solid #CED4D5");
        }
    }
    /**
    *
    *
    */
    targetMouseOut = function (obj) {
        if ( YAHOO.util.DragDropMgr.dragCurrent != null ) {
            YAHOO.util.Dom.get(YAHOO.util.DragDropMgr.dragCurrent.getDragEl().id).className = "groupDDc";
        }
        YAHOO.util.Dom.setStyle(obj, "background-color", "#f5f5f1");
        YAHOO.util.Dom.setStyle(obj, "border", "1px solid #f5f5f1");
    }
    
    /**
    *
    *
    */
    var iii = 0;
    var newItemInfo;
    targetMouseUp = function (obj, treeObj) {
        if ( YAHOO.util.DragDropMgr.dragCurrent != null ) {
            var node = treeObj.getNodeByProperty("divid", obj.id);
            var oldCatId = 0;
            if ( YAHOO.util.DragDropMgr.dragCurrent.relatedNode == null ) {
                YAHOO.util.Dom.get("HoldingGroupBox").removeChild(YAHOO.util.Dom.get(YAHOO.util.DragDropMgr.dragCurrent.id));
            }else {
                if ( YAHOO.util.DragDropMgr.dragCurrent.parentNode == node ) return;
                oldCatId = YAHOO.util.DragDropMgr.dragCurrent.parentNode.data.catid;
                tmpTreeObj = YAHOO.util.DragDropMgr.dragCurrent.relatedNode.data.treeObj;
                tmpTreeObj.removeNode(YAHOO.util.DragDropMgr.dragCurrent.relatedNode);
                YAHOO.util.DragDropMgr.dragCurrent.parentNode.refresh();
                YAHOO.util.DragDropMgr.dragCurrent.parentNode.collapse();
                YAHOO.util.DragDropMgr.dragCurrent.parentNode.expand();
            }
            var divId = 'treegroupitem' + iii; iii ++;
            var re = /_div/;
            var nodeObj = {
                label : '<div class="znbHierarchyCategoryGroupLabel" id="' + divId + '"' +
                        ' onMouseUp="targetItemMouseUp(this, ' + treeObj.id.replace(re, "") + ' );"' +
                        ' onMouseOver="targetItemMouseOver(this);"' +
                        ' onMouseOut="targetItemMouseOut(this);"' +
                        '>' +
                        YAHOO.util.DragDropMgr.dragCurrent.groupName +
                        '<div id="' + divId + '_selector" style="display:none;" align="left" class="znbHierarchyUnderline"><div><div><img src="'+AppTheme.images+'/decorators/pix.gif" border="0"></div></div></div>' +
                        '</div>',
                divid : divId,
                treeObj : treeObj,
                groupID : YAHOO.util.DragDropMgr.dragCurrent.groupID
                //'<div id="' + divId + '_selector" style="display:none;" align="left"><img src="/img/hierarchy/arrow.gif" border="0"></div>' +
            }
            var newNode = new YAHOO.widget.TextNode(nodeObj, node, false);
            newNode.labelStyle = "";
            node.collapse();
            node.expand();

            var params = {};
            params.divId        = divId;
            params.relatedNode  = newNode;
            params.parentNode   = node;
            params.groupID      = YAHOO.util.DragDropMgr.dragCurrent.groupID;
            params.groupName    = YAHOO.util.DragDropMgr.dragCurrent.groupName;
            YAHOO.util.Event.onAvailable(divId, createTreeDDProxy, params);

            xajax_add_item(node.data.catid, YAHOO.util.DragDropMgr.dragCurrent.groupID, oldCatId);
        }
    }
    createTreeDDProxy = function(params) {
        var dragged = new TreeDDProxy(params.divId);
        dragged.relatedNode  = params.relatedNode;
        dragged.parentNode   = params.parentNode;
        dragged.groupID      = params.groupID;
        dragged.groupName    = params.groupName;
        reloadDDobjects();
    }
    reloadDDobjects = function () {
        for (var i in YAHOO.util.DragDropMgr.ids) {
            for ( var j in YAHOO.util.DragDropMgr.ids[i] ) {
                var dd = YAHOO.util.DragDropMgr.ids[i][j];
                var dragged = new TreeDDProxy(dd.id);
                dragged.relatedNode  = dd.relatedNode;
                dragged.parentNode   = dd.parentNode;
                dragged.groupID      = dd.groupID;
                dragged.groupName    = dd.groupName;
            }
        }
    }
    
    /**
    *
    *
    */
    targetItemMouseUp = function (obj, treeObj) {
        if ( YAHOO.util.DragDropMgr.dragCurrent != null ) {
            var targetNode = treeObj.getNodeByProperty("divid", obj.id);
            var oldCatId = 0;
            if ( YAHOO.util.DragDropMgr.dragCurrent.relatedNode != null ) {
                if ( obj.id == YAHOO.util.DragDropMgr.dragCurrent.relatedNode.data.divid ) return;  // элемент в себя же

                var sourceNode = YAHOO.util.DragDropMgr.dragCurrent.relatedNode;
                if ( sourceNode.parent == targetNode.parent ) { //  внутри одного парента
                    sourceNode.insertAfter(targetNode);
                    sourceNode.parent.refresh();
                    YAHOO.util.DragDropMgr.stopDrag();
                    YAHOO.util.DragDropMgr.lock();
                    reloadDDobjects();
                    xajax.addAfterResponseFunction('unlockDragDropMgr');
                    xajax_order_category(targetNode.parent.data.catid, getChildrenForSave(targetNode.parent), 'none', 0);
                } 
                /**
                *   move note from one parent into other
                */
                else {
                    //  remove note from first parent
                    oldCatId = YAHOO.util.DragDropMgr.dragCurrent.parentNode.data.catid;
                    tmpTreeObj = YAHOO.util.DragDropMgr.dragCurrent.relatedNode.data.treeObj;
                    tmpTreeObj.removeNode(YAHOO.util.DragDropMgr.dragCurrent.relatedNode);
                    YAHOO.util.DragDropMgr.dragCurrent.parentNode.refresh();
                    YAHOO.util.DragDropMgr.dragCurrent.parentNode.collapse();
                    YAHOO.util.DragDropMgr.dragCurrent.parentNode.expand();
                    //  insert node into other parent
                    var divId = 'treegroupitem' + iii; iii ++;
                    var re = /_div/;
                    var nodeObj = {
                        label : '<div class="znbHierarchyCategoryGroupLabel" id="' + divId + '"' +
                                ' onMouseUp="targetItemMouseUp(this, ' + treeObj.id.replace(re, "") + ' );"' +
                                ' onMouseOver="targetItemMouseOver(this);"' +
                                ' onMouseOut="targetItemMouseOut(this);"' +
                                '>' +
                                YAHOO.util.DragDropMgr.dragCurrent.groupName +
                                '<div id="' + divId + '_selector" style="display:none;" align="left" class="znbHierarchyUnderline"><div><div><img src="'+AppTheme.images+'/decorators/pix.gif" border="0"></div></div></div>' +
                                '</div>',
                        divid : divId,
                        treeObj : treeObj,
                        groupID : YAHOO.util.DragDropMgr.dragCurrent.groupID
                        //'<div id="' + divId + '_selector" style="display:none;" align="left"><img src="/img/hierarchy/arrow.gif" border="0"></div>' +
                    }
                    var newNode = new YAHOO.widget.TextNode(nodeObj, targetNode.parent, false);
                    newNode.labelStyle = "";
                    newNode.insertAfter(targetNode);
                    targetNode.parent.collapse();
                    targetNode.parent.expand();

                    var params = {};
                    params.divId        = divId;
                    params.relatedNode  = newNode;
                    params.parentNode   = targetNode.parent;
                    params.groupID      = YAHOO.util.DragDropMgr.dragCurrent.groupID;
                    params.groupName    = YAHOO.util.DragDropMgr.dragCurrent.groupName;
                    YAHOO.util.Event.onAvailable(divId, createTreeDDProxy, params);
                    //xajax_add_item(targetNode.parent.data.catid, YAHOO.util.DragDropMgr.dragCurrent.groupID, oldCatId);
                    YAHOO.util.DragDropMgr.stopDrag();
                    YAHOO.util.DragDropMgr.lock();
                    reloadDDobjects();
                    xajax.addAfterResponseFunction('unlockDragDropMgr');
                    xajax_order_category(targetNode.parent.data.catid, getChildrenForSave(targetNode.parent), 'none', oldCatId);
                }
            } else {
                YAHOO.util.Dom.get("HoldingGroupBox").removeChild(YAHOO.util.Dom.get(YAHOO.util.DragDropMgr.dragCurrent.id));
                var divId = 'treegroupitem' + iii; iii ++;
                var re = /_div/;
                var nodeObj = {
                    label : '<div class="znbHierarchyCategoryGroupLabel" id="' + divId + '"' +
                            ' onMouseUp="targetItemMouseUp(this, ' + treeObj.id.replace(re, "") + ' );"' +
                            ' onMouseOver="targetItemMouseOver(this);"' +
                            ' onMouseOut="targetItemMouseOut(this);"' +
                            '>' +
                            YAHOO.util.DragDropMgr.dragCurrent.groupName +
                            '<div id="' + divId + '_selector" style="display:none;" align="left" class="znbHierarchyUnderline"><div><div><img src="'+AppTheme.images+'/decorators/pix.gif" border="0"></div></div></div>' +
                            '</div>',
                    divid : divId,
                    treeObj : treeObj,
                    groupID : YAHOO.util.DragDropMgr.dragCurrent.groupID
                    //'<div id="' + divId + '_selector" style="display:none;" align="left"><img src="/img/hierarchy/arrow.gif" border="0"></div>' +
                }
                var newNode = new YAHOO.widget.TextNode(nodeObj, targetNode.parent, false);
                newNode.labelStyle = "";
                newNode.insertAfter(targetNode);
                targetNode.parent.collapse();
                targetNode.parent.expand();

                var params = {};
                params.divId        = divId;
                params.relatedNode  = newNode;
                params.parentNode   = targetNode.parent;
                params.groupID      = YAHOO.util.DragDropMgr.dragCurrent.groupID;
                params.groupName    = YAHOO.util.DragDropMgr.dragCurrent.groupName;
                YAHOO.util.Event.onAvailable(divId, createTreeDDProxy, params);
                //xajax_add_item(targetNode.parent.data.catid, YAHOO.util.DragDropMgr.dragCurrent.groupID, oldCatId);
                YAHOO.util.DragDropMgr.stopDrag();
                YAHOO.util.DragDropMgr.lock();
                reloadDDobjects();
                xajax.addAfterResponseFunction('unlockDragDropMgr');
                xajax_order_category(targetNode.parent.data.catid, getChildrenForSave(targetNode.parent), 'none', 0);
            }
        }
        YAHOO.util.Dom.setStyle(obj.id + '_selector', "display", "none");
    }
    function unlockDragDropMgr() {
        YAHOO.util.DragDropMgr.unlock();
    }
    

    targetItemMouseOver = function (obj) {
        if ( YAHOO.util.DragDropMgr.dragCurrent != null ) {
            if (
                YAHOO.util.DragDropMgr.dragCurrent.relatedNode == null ||
                ( YAHOO.util.DragDropMgr.dragCurrent.relatedNode != null && obj.id != YAHOO.util.DragDropMgr.dragCurrent.relatedNode.data.divid )
            )
            {
                YAHOO.util.Dom.setStyle(obj.id + '_selector', "display", "");
                YAHOO.util.Dom.get(YAHOO.util.DragDropMgr.dragCurrent.getDragEl().id).className = "groupDDo";
            }
        }
    }
    targetItemMouseOut = function (obj) {
        if ( YAHOO.util.DragDropMgr.dragCurrent != null ) {
            YAHOO.util.Dom.get(YAHOO.util.DragDropMgr.dragCurrent.getDragEl().id).className = "groupDDc";
        }
        YAHOO.util.Dom.setStyle(obj.id + '_selector', "display", "none");
    }
    getChildrenForSave = function (node) {
        var childrenForSave = new Array();
        if ( node.children.length > 0 ) {
            for(var i = 0; i < node.children.length; i++) {
                childrenForSave[childrenForSave.length] = {groupID : node.children[i].data.groupID};
            }
        }
        return childrenForSave;
    }

    /**
    *
    *
    */
    holdingBoxMouseOver = function () {
        if ( YAHOO.util.DragDropMgr.dragCurrent != null ) {
            YAHOO.util.Dom.get(YAHOO.util.DragDropMgr.dragCurrent.getDragEl().id).className = "groupDDo";
            YAHOO.util.Dom.setStyle(this, "background-color", "#fff");
        }
    }
    
    /**
    *
    *
    */
    holdingBoxMouseOut = function () {
        if ( YAHOO.util.DragDropMgr.dragCurrent != null ) {
            YAHOO.util.Dom.get(YAHOO.util.DragDropMgr.dragCurrent.getDragEl().id).className = "groupDDc";
        }
        YAHOO.util.Dom.setStyle(this, "background-color", "#f5f5f1");
    }
    
    /**
    *
    *
    */
    holdingBoxMouseUp = function () {
        if ( YAHOO.util.DragDropMgr.dragCurrent != null ) {
            if ( YAHOO.util.DragDropMgr.dragCurrent.relatedNode == null ) {
                YAHOO.util.Dom.setStyle(this, "background-color", "f5f5f1");
                return;
            }else {
                treeObj = YAHOO.util.DragDropMgr.dragCurrent.relatedNode.data.treeObj;
                treeObj.removeNode(YAHOO.util.DragDropMgr.dragCurrent.relatedNode);
                YAHOO.util.DragDropMgr.dragCurrent.parentNode.refresh();
                YAHOO.util.DragDropMgr.dragCurrent.parentNode.collapse();
                YAHOO.util.DragDropMgr.dragCurrent.parentNode.expand();
                xajax_remove_item(YAHOO.util.DragDropMgr.dragCurrent.parentNode.data.catid, YAHOO.util.DragDropMgr.dragCurrent.groupID);
            }
            var div = document.createElement("div");
            div.className = 'znbHierarchyDndInner';
            div.innerHTML = YAHOO.util.DragDropMgr.dragCurrent.groupName;
            this.appendChild(div);

            var dd = new GroupDDProxy(div);
            dd.groupID       = YAHOO.util.DragDropMgr.dragCurrent.groupID;
            dd.groupName     = YAHOO.util.DragDropMgr.dragCurrent.groupName;
            reloadDDobjects();
        }
        YAHOO.util.Dom.setStyle(this, "background-color", "#f5f5f1");
    }
    
    
    /**
    *
    */
    GroupDDProxy = function(id, sGroup, config) {
        if (id) {
            this.init(id, sGroup, config);
            this.initFrame();
        }
        var parentNode = null;
        var relatedNode = null;
        var groupID = null;
        var groupName = null;
    };
    
    YAHOO.extend(GroupDDProxy, YAHOO.util.DDProxy);
    GroupDDProxy.prototype.startDrag = function (e) {
        this.deltaX = -10;
        this.deltaY = 0;
        var d = this.getDragEl();
        d.innerHTML = this.getEl().innerHTML;
        d.style.border = this.getEl().style.border;
        d.style.width = "180px";
        d.className = "groupDDc";
    }
    
    GroupDDProxy.prototype.endDrag = function (e) {
        YAHOO.util.Dom.get(this.getDragEl().id).className = "groupDDc";
    }
        
    /**
    *
    */
    TreeDDProxy = function(id, sGroup, config) {
        if (id) {
            this.init(id, sGroup, config);
            this.initFrame();
        }
        var parentNode      = null;
        var relatedNode     = null;
        var groupID         = null;
        var groupName       = null;
    };
    
    YAHOO.extend(TreeDDProxy, YAHOO.util.DDProxy);
    TreeDDProxy.prototype.startDrag = function (e) {
        this.deltaX = -10;
        this.deltaY = 0;
        var d = this.getDragEl();
        d.innerHTML = this.getEl().innerHTML;
        d.style.border = this.getEl().style.border;
        d.style.width = "180px";
        d.className = "groupDDc";
    }
    TreeDDProxy.prototype.endDrag = function (e) {
        YAHOO.util.Dom.get(this.getDragEl().id).className = "groupDDc";
    }
    
    /**
    *
    */
    initDragedObjects = function () {
        if ( document.getElementById('HoldingGroupBox') ) {
            var el = new YAHOO.util.Element('HoldingGroupBox');
            var items = el.getElementsByTagName('div');
            if ( items.length > 0 ) {
                for ( var i = 0; i < items.length; i++ ) {
                    var cEl = items[i];
                    var dd = new GroupDDProxy(cEl);
                    dd.groupID       = cEl.getAttribute('groupID');
                    dd.groupName     = cEl.innerHTML;
                }
            }

            YAHOO.util.Event.on(YAHOO.util.Dom.get("HoldingGroupBox"), "mouseup",   holdingBoxMouseUp);
            YAHOO.util.Event.on(YAHOO.util.Dom.get("HoldingGroupBox"), "mouseover", holdingBoxMouseOver);
            YAHOO.util.Event.on(YAHOO.util.Dom.get("HoldingGroupBox"), "mouseout",  holdingBoxMouseOut);
        }
        jsTree();
    }
    
    /*
    +---------------------------------------------------------
    |
    |   Hierarchy Methods
    |
    +---------------------------------------------------------
    */
    
    function changeCurrentHierarchy(id, url)
    {
        document.location.replace(url + 'hid/' + id + '/');
    }
    function addHierarchyHandler()
    {
        var hname = document.getElementById('new_hname').value;
        if ( hname.trim() == "" ) {
            alert("Hierarchy name is not defined");
            return false;
        }
        popup_window.close();
        xajax_add_hierarchy_handler(hname);
    }
    function renameHierarchyHandler(curr_hid) {
        var hname = document.getElementById('hname').value;
        if ( hname.trim() == "" ) {
            alert("Hierarchy name is not defined");
            return false;
        }
        popup_window.close();
        xajax_rename_hierarchy_handler(curr_hid, hname);
    }
    function deleteHierarchyHandler(curr_hid) {
        popup_window.close();
        xajax_delete_hierarchy_handler(curr_hid);
    }
    
    /*
    +---------------------------------------------------------
    |
    |   Constraints Methods
    |
    +---------------------------------------------------------
    */    
    
    function saveConstraints(cur_hid) {
            data = {
                hierarchy_type  : document.getElementById('hierarchy_type').options[document.getElementById('hierarchy_type').selectedIndex].value,
                category_type   : (document.getElementById('category_type')) ? document.getElementById('category_type').options[document.getElementById('category_type').selectedIndex].value : 0,
                category_focus  : (document.getElementById('category_focus')) ? document.getElementById('category_focus').options[document.getElementById('category_focus').selectedIndex].value : 0
            }
        xajax_save_constraints(cur_hid, data)
    }
    function changeConstraints(curr_hid, level, value) {
        xajax_change_constraints(curr_hid, level, value);
    }
    
    /*
    +---------------------------------------------------------
    |
    |   Options Methods
    |
    +---------------------------------------------------------
    */
    
    function saveOptions(curr_hid)
    {
        options = new Array();
        options['isdefault'] = (document.getElementById('default').checked == true) ? 1 : 0;
        options['present_custom_levels'] = (document.getElementById('present_custom_levels').checked) ? 1 : 0;
        if ( document.getElementById('no_third_level') ) {
            options['no_third_level'] = (document.getElementById('no_third_level').checked) ? 1 : 0;
        }
        if ( document.getElementById('break_after') ) {
            options['break_after'] = document.getElementById('break_after').options[document.getElementById('break_after').selectedIndex].value;
        }
        if ( document.getElementById('group_display') ) {
            options['group_display'] = document.getElementById('group_display').options[document.getElementById('group_display').selectedIndex].value;
        }
        xajax_save_options(curr_hid, options);
    }
    
    /*
    +---------------------------------------------------------
    |
    |   Category Grouping Methods
    |
    +---------------------------------------------------------
    */
    
    
    /**
    *   change name of custom category
    */
    function categoryChangeHandler(catid, groupid, hid)
    {
        var catinput = document.getElementById('cat_' + catid);
        //alert(catinput.style.border);
        if ( catinput.value.trim() != "" ) {
			catinput.style.backgroundColor = "#fff";
            xajax_category_change(catid, groupid, hid, catinput.value);
        } else {
            alert("Incorrect name of category. \nNew category name hasn't been saved.");
			catinput.style.backgroundColor = "#FFD2B4";
            catinput.focus();
        }
    }
    
    /**
    *   remove custom category
    */
    function removeCategory(catid, groupid, hid)
    {
        xajax_remove_category(catid, groupid, hid);
    }
    
    function showHoldingTankHelp() {
        var helpBox = YAHOO.util.Dom.get('HoldingTankHelpBox');
        if ( helpBox.style.display == 'none' ) {
            helpBox.style.display = '';
        } else {
            helpBox.style.display = 'none';
        }
    }
    
    /**
     * 
     */
    function NoThirdLevelSortingChecked(control)
    {
    	if ( control.checked == true ) {
    		var subBox = document.getElementById('BreakToNextLevelBox');
    		if ( subBox ) subBox.style.display = 'none';
    	} else {
    		var subBox = document.getElementById('BreakToNextLevelBox');
    		if ( subBox ) subBox.style.display = '';
    	}
    }
