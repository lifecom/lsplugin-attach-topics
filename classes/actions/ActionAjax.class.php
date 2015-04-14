<?php

class PluginAttachtopics_ActionAjax extends PluginAttachtopics_Inherit_ActionAjax
{

    protected function RegisterEvent()
    {
        $this->AddEventPreg('/^attachtopics$/i', '/^find$/', 'EventAttachTopicsFindTopic');
        parent::RegisterEvent();
    }

    /**
     * Нахождение подходящих топиков
     *
     */
    protected function EventAttachTopicsFindTopic()
    {
        if (!$this->oUserCurrent) {
            $this->Message_AddErrorSingle($this->Lang_Get('need_authorization'), $this->Lang_Get('error'));

            return;
        }

        $aBlog = $this->Blog_GetAccessibleBlogsByUser($this->oUserCurrent);

        $oPersBlog = $this->Blog_GetPersonalBlogByUserId($this->oUserCurrent->getId());
        if ($oPersBlog) {
            $aBlog = array_merge($aBlog, array($oPersBlog->getId()));
        }

        $aFilter = array(
            'topic_publish' => 1,
            'blog_id' => $aBlog,
            'title_like' => getRequest('titlePart'),
            'exclude_topics' => getRequest('excludeTopics')
        );

        if (Config::Get('plugin.stickytopics.personal_sticky_topics_kind') != 'all') {
            $aFilter['user_id'] = $this->oUserCurrent->getId();

            if (Config::Get('plugin.stickytopics.personal_sticky_topics_kind') == 'personal') {
                $oPersBlog = $this->Blog_GetPersonalBlogByUserId($this->oUserCurrent->getId());
                if (!$oPersBlog) {
                    $aFilter['user_id'] = -1;
                } else {
                    $aBlog = array($oPersBlog->getId());
                }
            }
        }

        $aFilter['blog_id'] = $aBlog;

        $aTopic = $this->Topic_GetTopicsByFilter($aFilter, 1, 20);

        $oViewer = $this->Viewer_GetLocalViewer();

        $oViewer->Assign('aTopic', $aTopic['collection']);

        $this->Viewer_AssignAjax(
            'topicData',
            $oViewer->Fetch(Plugin::GetTemplatePath(__CLASS__) . 'topic_list.tpl')
        );
    }

}

?>