<link rel="stylesheet" type="text/css" href="{$AppTheme->common->css}/tree.css" media="screen" />
{literal}
    <style>
        .ygtvitem table tbody tr td {
            padding : 0px;
        }
        .ygtvlabel, .ygtvlabel:link,
        .ygtvlabel:visited, .ygtvlabel:hover{
            margin-left : 0px;
        }
        .lang-export-table-td {
            padding 3px !important;
        }
        .znResult a {
            color:#1680BE!important;
        }
    </style>
{/literal}
<script type="text/javascript" src="{$JS_URL}/yui/yahoo/yahoo-min.js" ></script>
<script type="text/javascript" src="{$JS_URL}/yui/event/event-min.js" ></script>
<script type="text/javascript" src="{$JS_URL}/yui/dom/dom-min.js" ></script>
<script type="text/javascript" src="{$JS_URL}/yui/animation/animation.js" ></script>
<script type="text/javascript" src = "{$JS_URL}/yui/treeview/treeview.js" ></script>
<script type="text/javascript" src = "{$JS_URL}/yui/connection/connection-min.js" ></script>
{literal}
    <script type="text/javascript">
    var TranslateApplication = null;
    if ( !TranslateApplication ) {
    	TranslateApplication = function () {
    		return {
                currentTreeNode : null,
                workTextNode    : null,
    			init : function () {
                    loadFilesTree();
    			},
                submitEditPhraseForm : function() {
                    var messageKey = document.getElementById('messageKey').value;
                    var messageFile = document.getElementById('messageFile').value;
                    var params = xajax.getFormValues('editPhraseForm');
                    xajax_editTranslateFile(messageFile, messageKey, params);
                },
                handleImportFile : function () {
                    var callback = {
                        upload: TranslateApplication.handleImportFileResponse
                    }
                    var oForm = YAHOO.util.Dom.get('importForm');
                    YAHOO.util.Connect.setForm(oForm, true);
                    try {
                        var cObj = YAHOO.util.Connect.asyncRequest('POST', '{/literal}{$BASE_URL}/{$LOCALE}/adminarea/translateImport/{literal}', callback);
                    } catch(ex) {                
                        alert("Incorrect file name");
                    }                				
                },
                handleImportFileResponse : function (oResponse) {
                    xajax.processResponse(oResponse.responseXML);
                },
                handleCheckAllLocales : function () {
                    var check_all = document.getElementById('check_all');
                    var importForm = document.getElementById('importForm');
                    var len = importForm.elements.length;
                    for (var i = 0; i < len; i++ ) {
                        if ( 'checkbox' == importForm.elements[i].type && importForm.elements[i].value != '{/literal}{$defLocale}{literal}' ) {
                            if ( check_all.checked == true ) {                        
                                importForm.elements[i].checked = true;
                            } else {
                                importForm.elements[i].checked = false;
                            }                            
                        }
                    }
                }
    		}
    	}();
    };
    YAHOO.util.Event.onDOMReady(TranslateApplication.init);
    </script>

    <script type="text/javascript">
        function loadFilesTree() 
        {
            {/literal}{$FilesTreeJS}{literal}        
        }  
    </script>    
{/literal}

<div class="znWidget1 znWidgetInner3 znTRight">
    {*linkbutton style="float:right; margin-top:7px;" name="Create New Template" link=$admin->getAdminPath('newtemplate/')*}
</div>

<div class="znWidget1 znWidgetInner3">
    <div class="znResult-outer znGrayBG znGrayBorder">
        <div>
            <table cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td style="width:300px" valign="top">
                        <table cellspacing="0" cellpadding="0" class="znResult znTColor20">
                            <thead>
                                <tr>
                                    <th class="znTLeft">Files</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="3">
                                        <div id="filesContentDiv" style="min-height:300px;"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td style="width:10px">&nbsp;</td>
                    <td valign="top">
                        <table cellspacing="0" cellpadding="0" class="znResult">
                            <thead>
                                <tr>
                                    <th class="znTLeft">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="3" class="znTRight">
                                        <form method='post' name='currentDirectionForm' id='currentDirectionForm'>
                                        <select style="width:350px;" name='currentDirection' id='currentDirection'>
                                            <option value='all'>All languages</option>
                                            {foreach from=$LocalesSelect item='opt'}
                                            <option value={$opt.value} {if $currectDirection == $opt.value}selected{/if}>{$opt.label}</option>
                                            {/foreach}
                                        </select>
                                        <input type='submit' name='btnDirSubmit' value='Apply Direction'>
                                        </form>
                                    </td>
                                </tr>
                                <tr id="ExportImportTools" style="display:none;">
                                    <td colspan="4" class="znTRight">&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <div id="ListOfPhrasesBox">
                        {include file="adminarea/translate/translate.tools.phrases.template.tpl"}
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
