<?php

/*-------------------------------------------------------
*
*   StickyTopics v2.
*   Copyright © 2012 Alexei Lukin
*
*--------------------------------------------------------
*
*   Official site: imthinker.ru/stickytopics2
*   Contact e-mail: kerbylav@gmail.com
*
---------------------------------------------------------
*/

class PluginAttachtopics_HookAttachtopics extends Hook
{

    public function RegisterHook()
    {
        $oUserCurrent = $this->User_GetUserCurrent();
        if (!$oUserCurrent) {
            return;
        }

        $this->AddHook('template_form_add_topic_topic_end',
            'AttachTopicsTemplate'); // место для вывода выбора топиков при создании топика

//        $this->AddHook('template_menu_blog_edit_admin_item', 'AddBlogEditMenu');
//        $this->AddHook('template_menu_profile_created_item', 'AddPersonalEditMenu');
//        $this->AddHook('template_admin_action_item', 'AddAdminEditMenu');
        $this->AddHook('template_st_assign_filepath', 'AssignFilePath');
        $this->AddHook('template_st_assign_webpath', 'AssignWebPath');
        $this->AddHook('topic_add_after', 'TopicAddAfter');
        $this->AddHook('topic_edit_after', 'TopicEditAfter');
        $this->AddHook('topic_edit_show', 'TopicEditShow');
        $this->AddHook('topic_show', 'TopicShow');

        $sHookName = 'template_';
        if (!Config::Get('plugin.attachtopics.hook_for_attach') OR (Config::Get('plugin.attachtopics.hook_for_attach') == '')) {
            $sHookName = $sHookName . 'topic_show_end';
        } else {
            $sHookName = $sHookName . Config::Get('plugin.attachtopics.hook_for_attach');
        }
        $this->AddHook($sHookName, 'TemplateTopicShow');
    }

    public function TemplateTopicShow($aParams)
    {
        $oTopic = $aParams['topic'];
        $this->Viewer_Assign('oTopic', $oTopic);

        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'attach_topics.tpl');
    }

    public function TopicShow($aParams)
    {
        $oTopic = $aParams['oTopic'];

        $aAttachTopic = array();
        if ($aAttach = $this->PluginAttachtopics_ModuleAttach_GetAttachTopicsByTopicId($oTopic->getId())) {
            foreach ($aAttach as $oAttach) {
                $aAttachTopic[] = $this->Topic_GetTopicById($oAttach->getAttachId());
            }
            $this->Viewer_Assign('aAttachTopic', $aAttachTopic);
        }

        return $aParams;
    }

    public function TopicEditShow($aParams)
    {
        $oTopic = $aParams['oTopic'];

        $aTopic = array();
        if ($aAttach = $this->PluginAttachtopics_ModuleAttach_GetAttachTopicsByTopicId($oTopic->getId())) {
            foreach ($aAttach as $oAttach) {
                $aTopic[] = $this->Topic_GetTopicById($oAttach->getAttachId());
            }
            $this->Viewer_Assign('aTopic', $aTopic);
        }

        return $aParams;
    }

    public function TopicEditAfter($aParams)
    {
        $oTopic = $aParams['oTopic'];

        if ($aTopicId = getRequest('attach_topic')) {
            $this->PluginAttachtopics_ModuleAttach_DeleteAttachByTopicId($oTopic->getId());
            foreach ($aTopicId as $key => $iTopicId) {
                $this->PluginAttachtopics_ModuleAttach_SetAttach($oTopic->getId(), $iTopicId, $key + 1);
            }
        }

        return $aParams;
    }

    public function TopicAddAfter($aParams)
    {
        $oTopic = $aParams['oTopic'];

        if ($aTopicId = getRequest('attach_topic')) {
            foreach ($aTopicId as $key => $iTopicId) {
                $this->PluginAttachtopics_ModuleAttach_SetAttach($oTopic->getId(), $iTopicId, $key + 1);
            }
        }

        return $aParams;
    }

    public function AttachTopicsTemplate()
    {
        $this->Viewer_AppendStyle(Plugin::GetTemplatePath(__CLASS__) . 'css/style.css');
        $this->Viewer_AppendScript(Plugin::GetTemplatePath(__CLASS__) . 'js/stickytopics.js');

        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'form_attach.tpl');
    }

    public function AddBlogEditMenu($aParams)
    {
        $res = $this->Viewer_Fetch($this->PluginAttachtopics_Attachtopics_GetTemplateFilePath(__CLASS__,
            'blog_edit_menu.tpl'));

        return $res;
    }

    public function AddPersonalEditMenu($aParams)
    {
        if (!$oUserCurrent = $this->User_GetUserCurrent()) {
            return;
        }

        if ($aParams['oUserProfile']->getId() != $oUserCurrent->getId()) {
            return;
        }

        if (!$this->ACL_CanStickTopic($aParams['oUserProfile'], 'personal')) {
            return;
        }

        $res = $this->Viewer_Fetch($this->PluginStickytopics_Stickytopics_GetTemplateFilePath(__CLASS__,
            'personal_edit_menu.tpl'));

        return $res;
    }

    public function AddAdminEditMenu($aParams)
    {
        $res = $this->Viewer_Fetch($this->PluginStickytopics_Stickytopics_GetTemplateFilePath(__CLASS__,
            'admin_edit_menu.tpl'));

        return $res;
    }

    public function AssignFilePath($aParams)
    {
        return Plugin::GetTemplatePath(__CLASS__) . $aParams['sFilename'];
    }

    public function AssignWebPath($aParams)
    {
        return Plugin::GetTemplateWebPath(__CLASS__) . $aParams['sFilename'];
    }

}
