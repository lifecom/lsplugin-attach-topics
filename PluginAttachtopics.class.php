<?php

/**
 * Запрещаем напрямую через браузер обращение к этому файлу.
 */
if (!class_exists('Plugin')) {
    die('Hacking attempt!');
}

class PluginAttachtopics extends Plugin
{

    protected $aInherits = array(
        'action' => array(
            'ActionAjax',
        ),
    );

    public function Activate()
    {
        if (!$this->isTableExists('prefix_attach_topics')) {
            /**
             * При активации выполняем SQL дамп
             */
            $this->ExportSQL(dirname(__FILE__) . '/install.sql');
        }

        return true;
    }

    public function Init()
    {
        $this->Viewer_AppendStyle(Plugin::GetTemplatePath(__CLASS__) . 'css/style.css');
        $this->Viewer_AppendScript(Plugin::GetTemplatePath(__CLASS__) . 'js/attachtopics.js');

        return true;
    }

}
