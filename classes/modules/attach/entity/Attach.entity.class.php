<?php

class PluginAttachtopics_ModuleAttach_EntityAttach extends Entity
{
    // Getters

    /*
     * @return int|null
     */
    public function getId()
    {
        return $this->_getDataOne('id');
    }

    public function getTopicId()
    {
        return $this->_getDataOne('topic_id');
    }

    public function getAttachId()
    {
        return $this->_getDataOne('attach_id');
    }

    public function getOrder()
    {
        return $this->_getDataOne('order');
    }

    // Setters

    /**
     * @param int $data
     */
    public function setId($data)
    {
        $this->_aData['id'] = $data;
    }

    public function setTopicId($data)
    {
        $this->_aData['topic_id'] = $data;
    }

    public function setAttachId($data)
    {
        $this->_aData['attach_id'] = $data;
    }

    public function setOrder($data)
    {
        $this->_aData['order'] = $data;
    }

}
