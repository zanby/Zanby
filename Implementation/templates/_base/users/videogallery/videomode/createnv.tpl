{literal}
<script type="text/javascript" src="/js/tinymceBlog/tiny_mce.js"></script>
<script>
    var TMheight = "300";
</script>
<script type="text/javascript" src="/js/tinymceStory/settings.js"></script>
{/literal}

<a href="{$currentUser->getUserPath('videos')}">{t}Back to Videos{/t}</a>

<div id="videosRows" class="prInnerSmallTop">
    {form from=$form enctype="multipart/form-data"}
    {form_errors_summary}
          <table class="prForm">
            <tr>
                <td class="prTRight"><label>{t}Title:{/t}</label></td>
                <td>
                  {form_text name="title" id="videoTitle" value=$params.title|escape:html}
                </td>
            </tr>
            <tr>
                <td class="prTRight"><label>{t}Description:{/t}</label></td>
                <td>
                    {form_textarea name="description" id="videoDescription" value=$params.description|escape:html}
                </td>
            </tr>
            <tr>
                <td class="prTRight"><label>{t}Tags:{/t}</label></td>
                <td>{form_text name="tags" id="videoTags" value=$params.tags|escape:"html"}</td>
            </tr>
            {if HTTP_CONTEXT == 'zccf' || HTTP_CONTEXT == 'zccf-alt' || HTTP_CONTEXT == 'zccf-base'}
            <tr>
                <td class="prTRight">&nbsp;</td>
                <td><div class="prTip">{t}Tags are a way to group your videos and are separated by spaces. Use quotes around multi-word tags like "Public LIbrary"{/t}</div></td>
            </tr>
            {/if}
            <tr>
                <td class="prTRight"><label>{t}Thumbnail{/t}</label></td>
                  <td>
                    {form_file name="customSrcImg" size="87"}
                  </td>
            </tr>
            <tr>
                <td class="prTRight"><label>{t}Privacy:{/t}</label></td>
                <td>
                  {form_radio name="isPrivate" id="isPrivate1" value="0" checked=$params.isPrivate|default:1}<label for="isPrivate1"> {t}Public{/t}</label>
                  {form_radio name="isPrivate" id="isPrivate2" value="1" checked=$params.isPrivate|default:1}<label for="isPrivate2"> {t}Private{/t}</label>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                <a class="prButton" href="#null" onClick="tinyMCE.execCommand( 'mceRemoveControl', true, 'videoDescription');document.{$form->name}.submit(); return false;"><span>{t}Create{/t}</span></a>&nbsp;
                <a class="prButton" href="{$currentUser->getUserPath('videos')}"><span>{t}Cancel{/t}</span></a>
                </td>
            </tr>
          </table>
      {/form}
      <script>
          tinyMCE.execCommand('mceAddControl', true, 'videoDescription');
      </script>

</div>
                        <!--</td>
                      </tr>
                    </table>-->



