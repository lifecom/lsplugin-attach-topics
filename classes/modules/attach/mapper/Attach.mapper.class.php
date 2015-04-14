<?php

class PluginAttachtopics_ModuleAttach_MapperAttach extends Mapper
{
    public function GetAttachTopicsByTopicId($iId)
    {
        $sql = "SELECT
						*
					FROM
						" . Config::Get('plugin.attachtopics.table.attach') . "
					WHERE
					    `topic_id` = ?;
        ";

        $aAttachTopics = array();
        if ($aRows = $this->oDb->select(
            $sql,
            $iId
        )
        ) {
            foreach ($aRows as $aRow) {
                $aAttachTopics[] = Engine::GetEntity('PluginAttachtopics_Attach_Attach', $aRow);
            }
        }

        return $aAttachTopics;
    }

    public function SetAttach(PluginAttachtopics_ModuleAttach_EntityAttach $oAttach)
    {
        $sql = "INSERT INTO " . Config::Get('plugin.attachtopics.table.attach') . "
			(
			    `topic_id`,
			    `attach_id`,
			    `order`
			)
			VALUES(?, ?, ?);
		";
        if ($iId = $this->oDb->query(
            $sql,
            $oAttach->getTopicId(),
            $oAttach->getAttachId(),
            $oAttach->getOrder()
        )
        ) {
            return $iId;
        }

        return false;
    }

    public function DeleteAttachByTopicId($iTopicId)
    {
        $sql = "DELETE FROM " . Config::Get('plugin.attachtopics.table.attach') . "
                    WHERE `topic_id` = ?d;";

        return $this->oDb->query($sql, $iTopicId);
    }
}