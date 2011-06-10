<?php
/**
*   Zanby Enterprise Group Family System
*
*    Copyright (C) 2005-2011 Zanby LLC. (http://www.zanby.com)
*
*    This program is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
*    To contact Zanby LLC, send email to info@zanby.com.  Our mailing
*    address is:
*
*            Zanby LLC
*            3611 Farmington Road
*            Minnetonka, MN 55305
*
* @category   Zanby
* @package    Zanby
* @copyright  Copyright (c) 2005-2011 Zanby LLC. (http://www.zanby.com)
* @license    http://zanby.com/license/     GPL License
* @version    <this will be auto generated>
*/

/**
 * Warecorp FRAMEWORK
 *
 * @package    Warecorp_Document
 * @copyright  Copyright (c) 2006
 * @author Artem Sukharev
 */

/**
 *
 *
 */
class BaseWarecorp_Document_Tree
{
    private $_db;
    private $_ownerType;
    private $_owner;
    private $_callback;
    private $_main_callback;
    private $_show_shared       = true;
    private $_show_documents    = true;
    private $_show_main_folder  = false;
    private $_main_folder_name  = 'MAIN';
    private $_callbackOnExpand;
    private $_build_children    = true;
    /**
     * @var boolean
     */
    private $_show_shared_all_family_groups = false;


    public function __construct($owner)
    {
        $this->_db = Zend_Registry::get("DB");
        $this->_owner = $owner;
        if ( $owner instanceof Warecorp_User) {
            $this->_ownerType = 'user';
        } elseif ( $owner instanceof Warecorp_Group_Simple) {
            $this->_ownerType = 'group';
        } elseif ( $owner instanceof Warecorp_Group_Family) {
            $this->_ownerType = 'group';
        } else {
            throw new Zend_Exception("Owner Type is invalid");
        }
    }
    /*
    *   Getters / Setters Methods
    *
    */
    
    /**
     * @param boolean $bool
     * @return Warecorp_Document_Tree
     */
    public function setSharedAllFamilyGroups( $bool )
    {
        $this->_show_shared_all_family_groups = (bool) $bool;
        return $this;
    }
    public function isSharedAllFamilyGroups()
    {
        return $this->_show_shared_all_family_groups;
    }
    public function setBuildChildren($bool)
    {
        $this->_build_children = (bool)$bool;
        return $this;
    }
    public function getBuildChildren()
    {
        return $this->_build_children;
    }
    public function getOwner() 
    {
        return $this->_owner;
    }
    public function getOwnerType() 
    {
        return $this->_ownerType;
    }
    public function getCallbackOnExpandFunction() 
    {
        return $this->_callbackOnExpand;
    }
    public function setCallbackOnExpandFunction( $value ) 
    {
        $this->_callbackOnExpand = $value;
        return $this;
    }
    public function getCallbackFunction() 
    {
        return $this->_callback;
    }
    public function setCallbackFunction( $value ) 
    {
        $this->_callback = $value;
        return $this;
    }
    public function getMainCallbackFunction() 
    {
        return $this->_main_callback;
    }
    public function setMainCallbackFunction( $value ) 
    {
        $this->_main_callback = $value;
        return $this;
    }
    public function setShowShared($boolean)
    {
        $this->_show_shared = (bool) $boolean;
        return $this;
    }
    public function isShowShared()
    {
        return $this->_show_shared;
    }
    public function setShowDocuments($boolean)
    {
        $this->_show_documents = (bool) $boolean;
        return $this;
    }
    public function isShowDocuments()
    {
        return $this->_show_documents;
    }
    public function setShowMainFolder($boolean)
    {
        $this->_show_main_folder = (bool) $boolean;
        return $this;
    }
    public function isShowMainFolder()
    {
        return $this->_show_main_folder;
    }
    public function setMainFolderName($value)
    {
        $this->_main_folder_name = $value;
        return $this;
    }
    public function getMainFolderName()
    {
        return $this->_main_folder_name;
    }
    public function getTree($div_id = 'tree_div_0')
    {
        $AppTheme = (Zend_Registry::isRegistered('AppTheme')) ? Zend_Registry::get('AppTheme') : null;
        
        $Script = 'tree_0 = new YAHOO.widget.TreeView("'.$div_id.'");';
        if ( $this->isShowMainFolder() ) {
            //$Script .= 'var tree_0_main_root_node = tree_0.getRoot();';
            $Script .= 'tree_0_main_root_node = tree_0.getRoot();';
            $Script .= "tmpObj = { href : 'javascript:void(0);', label : '".$this->generateMainFolderLabel(true, 'tree_0')."', id : 0, callbackParam : 0, oType : 'folder'};";
            $Script .= "tree_0_root_node_".$this->getOwnerType()."_".$this->getOwner()->getId()." = new YAHOO.widget.TextNode(tmpObj, tree_0_main_root_node, true);";
            $Script .= "tree_0_root_node_".$this->getOwnerType()."_".$this->getOwner()->getId().".labelStyle = '';";
        } else {
            //$Script .= "var tree_0_root_node_".$this->getOwnerType()."_".$this->getOwner()->getId()." = tree_0.getRoot();";
            $Script .= "tree_0_root_node_".$this->getOwnerType()."_".$this->getOwner()->getId()." = tree_0.getRoot();";
        }
        $Script .= $this->getChildren("tree_0", null);

        $Script .= 'tree_0.subscribe("expand", function(node) { ';
        $Script .= '    if (document.getElementById("tree_0_node_image_" + node.data.id)) {';
        $Script .= '        document.getElementById("tree_0_node_image_" + node.data.id).src = "'.( ($AppTheme) ? $AppTheme->images.'/documents' : IMG_URL ).'/bg-mydocs-folder.gif";'; // th.../zanby-product/documents/bg-mydocs-folder.gif
        $Script .= '    };';
        $Script .= '});';

        $Script .= 'tree_0.subscribe("collapse", function(node) { ';
        $Script .= '    if (document.getElementById("tree_0_node_image_" + node.data.id)) {';
        $Script .= '        document.getElementById("tree_0_node_image_" + node.data.id).src = "'.( ($AppTheme) ? $AppTheme->images.'/documents' : IMG_URL ).'/bg-mydocs-folder.gif";';
        $Script .= '    };';
        $Script .= '});';

        if ( $this->getCallbackFunction() !== null ) {
            $Script .= 'tree_0.subscribe("labelClick", function(node) {';
            $Script .= '   if ( node.data.callbackParam != undefined ) {';
            $Script .= '       '.$this->getCallbackFunction().'(node.data.callbackParam);';
            $Script .= '   };';
            $Script .= '});';
        }

        $Script .= 'tree_0.draw();';
        $Script .= 'tree_0.collapseAll();';
        $Script .= 'root_node.expand();';

        return $Script;
    }
    public function startTree($treeName, $divId)
    {
        $Script = "";
        //$Script .= 'var '.$treeName.' = new YAHOO.widget.TreeView("'.$divId.'");';
        $Script .= ''.$treeName.' = new YAHOO.widget.TreeView("'.$divId.'");';
        if ( $this->getCallbackOnExpandFunction() ) {
            $Script .= "".$treeName.".onExpand = function(node) { alert(1);".$this->getCallbackOnExpandFunction()."(node); };";
        }
        return $Script;
    }
    public function buildTree($treeName)
    {
        $Script = "";
        if ( $this->isShowMainFolder() ) {
            $Script .= ''.$treeName.'_main_root_node = '.$treeName.'.getRoot();';
            $Script .= "tmpObj = { href : 'javascript:void(0);', label : '".$this->generateMainFolderLabel(false, $treeName)."', id : 0, oType : 'main_folder', name: '".htmlspecialchars($this->getMainFolderName(),ENT_QUOTES)."', ownerType : '".$this->getOwnerType()."', ownerId : ".$this->getOwner()->getId()."};";
            $Script .= "".$treeName."_root_node_".$this->getOwnerType()."_".$this->getOwner()->getId()." = new YAHOO.widget.TextNode(tmpObj, ".$treeName."_main_root_node, true);";
            $Script .= $treeName."_root_node_".$this->getOwnerType()."_".$this->getOwner()->getId().".labelStyle = '';";
            if ( $this->getMainCallbackFunction() !== null ) {
                $Script .= $treeName."_root_node_".$this->getOwnerType()."_".$this->getOwner()->getId().".onLabelClick = function(node){ ".$this->getMainCallbackFunction()."(node);};";
            }
        } else {
            $Script .= "".$treeName."_root_node_".$this->getOwnerType()."_".$this->getOwner()->getId()." = ".$treeName.".getRoot();";
        }
        $Script .= $this->getChildren($treeName);
        return $Script;
    }
    public function endTree($treeName)
    {
        $AppTheme = (Zend_Registry::isRegistered('AppTheme')) ? Zend_Registry::get('AppTheme') : null;
        
        $Script = "";
        $Script .= $treeName.'.subscribe("expand", function(node) { ';
        $Script .= '    if (document.getElementById("node_image_" + node.data.id)) {';
        $Script .= '        document.getElementById("node_image_" + node.data.id).src = "'.( ($AppTheme) ? $AppTheme->images.'/documents' : IMG_URL ).'/bg-mydocs-folder.gif";';
        $Script .= '    };';
        $Script .= '});';

        $Script .= $treeName.'.subscribe("collapse", function(node) { ';
        $Script .= '    if (document.getElementById("node_image_" + node.data.id)) {';
        $Script .= '        document.getElementById("node_image_" + node.data.id).src = "'.( ($AppTheme) ? $AppTheme->images.'/documents' : IMG_URL ).'/bg-mydocs-folder.gif";';
        $Script .= '    };';
        $Script .= '});';

        if ( $this->getCallbackFunction() !== null ) {
            $Script .= $treeName.'.subscribe("labelClick", function(node) {';
            $Script .= '       '.$this->getCallbackFunction().'(node);';
            $Script .= '});';
        }

        $Script .= $treeName.'.draw();';
        $Script .= $treeName.'.collapseAll();';
        //$Script .= $treeName.'.getRoot().expand();';
        return $Script;
    }
    public function getChildren($treeName, $folder = null)
    {
        $Script = "";

        if ( !$this->getBuildChildren() )
            return $Script;

        $folderListObj = new Warecorp_Document_FolderList($this->getOwner());
        $list = $folderListObj->setFolder($folder)->setOrder('name ASC')->getList();
        if ( sizeof($list) != 0 ) {
            foreach ( $list as $item ) {
                $Script .= "tmpObj = { href : 'javascript:void(0);', label : '".$this->generateFolderLabel($item, true, $treeName)."', id : '".$item->getId()."', oType : 'folder', name : '".str_replace(array("\\","'"),array("\\\\","\'"),$item->getName())."', ownerType : '".$this->getOwnerType()."', ownerId : ".$this->getOwner()->getId().", callbackParam : '".$item->getId()."'};";
                $Script .= "node_".$item->getId()." = new YAHOO.widget.TextNode(tmpObj, ". (($folder === null)?$treeName."_root_node_".$this->getOwnerType()."_".$this->getOwner()->getId()."":"node_".$folder) .", true);";
                $Script .= 'node_'.$item->getId().'.labelStyle = "";';
                $Script .= $this->getChildren($treeName, $item->getId());
            }
        }
        if ( $this->isShowDocuments() ) {
            $documentListObj = new Warecorp_Document_List($this->getOwner());
            $doclist = $documentListObj->setFolder($folder)->setShowShared($this->isShowShared())->setOrder('original_name ASC')->getList();
            if ( sizeof($doclist) != 0 ) {
                foreach ( $doclist as $ditem ) {
                    $Script .= "tmpObj = { href : 'javascript:void(0);', label : '".$this->generateDocumentLabel($ditem, $treeName)."', id : '".$ditem->getId()."', oType : 'document', name : '".str_replace(array("\\","'"),array("\\\\","\'"),$ditem->getOriginalName())."', ownerType : '".$this->getOwnerType()."', ownerId : ".$this->getOwner()->getId().", callbackParam : '".$ditem->getId()."'};";
                    $Script .= "doc_node_".$ditem->getId()." = new YAHOO.widget.TextNode(tmpObj, ". (($folder === null)?$treeName."_root_node_".$this->getOwnerType()."_".$this->getOwner()->getId()."":"node_".$folder) .", true);";
                    $Script .= 'doc_node_'.$ditem->getId().'.labelStyle = "";';
                }
            }
        }
        return $Script;
    }
    private function generateMainFolderLabel($isExpanded, $treeName)
    {
        $AppTheme = (Zend_Registry::isRegistered('AppTheme')) ? Zend_Registry::get('AppTheme') : null;
        
        $label = '';
        $label .= '<div id="'.$treeName.'_main_node_div_'.$this->getOwner()->getId().'">';
        $label .= '    <table cellpadding="0" cellspacing="0">';
        $label .= '        <tr>';
        $label .= '            <td valigin="middle" style="padding: 3px 5px 0px 1px; width:17px;">';
        $label .= '                <img id="'.$treeName.'_main_node_image_'.$this->getOwner()->getId().'" src="'.( ($AppTheme) ? $AppTheme->images.'/documents' : IMG_URL ).'/bg-mydocs-folder.gif" border="0">';
        $label .= '            </td>';
        $label .= '            <td valigin="middle" id="'.$treeName.'_main_node_label_'.$this->getOwner()->getId().'" rootnode="'.$this->getOwner()->getId().'" roottype="'.$this->getOwnerType().'" class="tree-documents-folder-inactive" style="padding-top: 3px;">';
        $label .= '                <div class="drop-source">'.htmlspecialchars($this->getMainFolderName(),ENT_QUOTES).'</div>';
        $label .= '            </td>';
        $label .= '        </tr>';
        $label .= '    </table>';
        $label .= '</div>';

        //$label = $item->name;
        return $label;
    }
    static public function generateFolderLabel($item, $isExpanded, $treeName)
    {
        $AppTheme = (Zend_Registry::isRegistered('AppTheme')) ? Zend_Registry::get('AppTheme') : null;
        
        $label = '';
        $label .= '<div id="'.$treeName.'_node_div_'.$item->getId().'">';
        $label .= '    <table cellpadding="0" cellspacing="0">';
        $label .= '        <tr>';
        $label .= '            <td valigin="middle" style="padding: 3px 5px 0px 0px; width:17px;">';
        $label .= '                <img id="'.$treeName.'_node_image_'.$item->getId().'" src="'.( ($AppTheme) ? $AppTheme->images.'/documents' : IMG_URL ).'/bg-mydocs-folder.gif" border="0">';
        $label .= '            </td>';
        $label .= '            <td valigin="middle" id="'.$treeName.'_node_label_'.$item->getId().'" node="'.$item->getId().'" class="tree-documents-folder-inactive" style="padding-top: 3px;">';
        $label .= '                <div class="drop-source">'.htmlspecialchars($item->getName(),ENT_QUOTES).'</div>';
        $label .= '            </td>';
        $label .= '        </tr>';
        $label .= '    </table>';
        $label .= '</div>';

        //$label = $item->name;
        return $label;
    }
    static public function generateDocumentLabel($item, $treeName)
    {
//        $label = htmlspecialchars($item->getOriginalName(),ENT_QUOTES);
//        return $label;
        /**
		 * @author Artem Sukharev
		 * use theme for display document image
        if ( file_exists(DOC_ROOT."/img/tree/files/".strtolower($item->getFileExt()).".gif") ) {
            $image = "/img/tree/files/".strtolower($item->getFileExt()).".gif";
        } else {
            $image = "/img/tree/files/blank.gif";
        }
		*/
		$image = $item->getIconImg();
        $label = '';
        $label .= '<div id="'.$treeName.'_doc_node_div_'.$item->getId().'">';
        $label .= '    <table cellpadding="0" cellspacing="0">';
        $label .= '        <tr>';
        $label .= '            <td valigin="middle" style="padding: 3px 5px 0px 0px; width:17px;">';
        $label .= '                <img id="'.$treeName.'_doc_node_image_'.$item->getId().'" src="'.$image.'" border="0">';
        $label .= '            </td>';
        $label .= '            <td nowrap="nowrap" valigin="middle" id="'.$treeName.'_doc_node_label_'.$item->getId().'">';
        $label .= '                '.htmlspecialchars($item->getOriginalName(),ENT_QUOTES);
        $label .= '            </td>';
        $label .= '        </tr>';
        $label .= '    </table>';
        $label .= '</div>';
        return $label;
    }
}
