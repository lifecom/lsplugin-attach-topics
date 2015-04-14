<?php

class PluginAttachtopics_ModuleAttach extends Module
{
    protected $oMapper;

    /**
     * Инициализация
     */
    public function Init()
    {
//        parent::Init();
//        $conn = $this->Database_GetConnect();
        $this->oMapper = Engine::GetMapper(__CLASS__);

//        $this->oEventMapper = Engine::GetMapper(__CLASS__, 'Type', $conn);
//        var_dump(__CLASS__);
//        echo 145;
//        exit();
    }

    public function GetAttachTopicsByTopicId($iId)
    {
        if ($data = $this->oMapper->GetAttachTopicsByTopicId($iId)) {
            return $data;
        }

        return null;
    }

    public function SetAttach($iTopicId, $iAttachId, $iOrder)
    {
        if ($iId = $this->oMapper->SetAttach(
            Engine::GetEntity(
                'PluginAttachtopics_Attach_Attach',
                array(
                    'topic_id' => $iTopicId,
                    'attach_id' => $iAttachId,
                    'order' => $iOrder,
                )
            )
        )
        ) {
            return $iId;
        }

        return false;
    }

    public function DeleteAttachByTopicId($iTopicId)
    {
        return $this->oMapper->DeleteAttachByTopicId($iTopicId);
    }

}