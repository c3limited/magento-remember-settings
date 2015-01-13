<?php

/**
 * Class C3_RememberSettings_Model_System_Config_Source_Gridkeys
 */
class C3_RememberSettings_Model_System_Config_Source_Gridkeys
{
    public function toOptionArray()
    {
        $keys = array(
            'sort' => 'Sort Column',
            'dir' => 'Sort Direction',
            'filter' => 'Search Filters',
            'limit' => 'Rows per Page',
            'page' => 'Current Page Number'
        );

        $arr = array();
        foreach ($keys as $value => $label) {
            $arr[] = array('value' => $value,'label' => $label);
        }
        return $arr;
    }
}
