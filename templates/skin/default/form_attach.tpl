<p>
<div id='stickytopics_list'>
    <table class="table table-stickytopics-list">
        <thead>
        <tr>
            <th class='actions'>{$aLang.plugin.attachtopics.admin_table_actions}</th>
            <th class='title'>{$aLang.plugin.attachtopics.admin_table_topic_title}</th>
        </tr>
        </thead>

        <tbody id="stickytopics_list_body">
        {foreach from=$aTopic item=oTopic name=fr}
            {assign var="bStickyList" value=true}
            <tr class='{if $bStickyList}st_stickytopic{else}st_foundtopic{/if}' id='stickytopic_{$oTopic->getId()}'>
                <td>
                    <input type="hidden" name="attach_topic[]" value="{$oTopic->getId()}">
                    {hook run='st_assign_webpath' sFilename='images/add.png' assign='sTempPath'}
                    <a class='st_add' {if $bStickyList}style='visibility: hidden;'{/if}
                       title='{$aLang.plugin.attachtopics.admin_table_action_add}' href='#'
                       onclick='ls.attachtopics.addTopic({$oTopic->getId()}); return false;'><img
                                src='{$sTempPath}'/></a>
                    {hook run='st_assign_webpath' sFilename='images/totop_16.png' assign='sTempPath'}
                    <a class='st_up' {if $smarty.foreach.fr.first || !$bStickyList}style='visibility: hidden;'{/if}
                       title='{$aLang.plugin.attachtopics.admin_table_action_totop}' href='#'
                       onclick='ls.attachtopics.moveTopic({$oTopic->getId()},-1); return false;'><img
                                src='{$sTempPath}'/></a>
                    {hook run='st_assign_webpath' sFilename='images/tobottom_16.png' assign='sTempPath'}
                    <a class='st_down' {if $smarty.foreach.fr.last || !$bStickyList}style='visibility: hidden;'{/if}
                       title='{$aLang.plugin.attachtopics.admin_table_action_tobottom}' href='#'
                       onclick='ls.attachtopics.moveTopic({$oTopic->getId()},1); return false;'><img
                                src='{$sTempPath}'/></a>
                    {hook run='st_assign_webpath' sFilename='images/delete.png' assign='sTempPath'}
                    <a class='st_delete' {if !$bStickyList}style='visibility: hidden;'{/if}
                       title='{$aLang.plugin.attachtopics.admin_table_action_delete}' href='#'
                       onclick='ls.attachtopics.deleteTopic({$oTopic->getId()}); return false;'><img
                                src='{$sTempPath}'/></a>
                </td>
                <td><a href="{$oTopic->getUrl()}" target="_blank">({$oTopic->getId()}
                        ) {$oTopic->getTitle()|escape:'html'}</a></td>
            </tr>
        {/foreach}
        </tbody>
        <tr>
            <th class='actions'></th>
            <th class='title'></th>
        </tr>
    </table>
    {*{hook run='st_assign_filepath' sFilename='topic_list.tpl' assign='sTempPath'}*}
    {*{include file=$sTempPath bStickyList=true}*}
</div>

<div class="stickytopics">
    <p><label for="search_topic">{$aLang.plugin.attachtopics.search_topic_title}:</label>
        <input type="text" id="search_topic" name="search_topic" value="" class="input-text input-width-full"/>
        <small class="note">{$aLang.plugin.attachtopics.search_topic_title_note}</small>
    </p>
    <button type="submit" name="submit_blog_add" class="button button-primary"
            onclick='ls.attachtopics.findTopics($("#search_topic").val(),1);return false;'>{$aLang.plugin.attachtopics.search_find}</button>
</div>

<div id='stickytopics_find_list'></div>
</p>
