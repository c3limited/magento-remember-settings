<?php

/**
 * Class C3_RememberSettings_Helper_Data
 *
 * Default helper for C3 RememberSettings
 */
class C3_RememberSettings_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $_configPathPrefix = 'c3_remembersettings/';
    protected $_allowedActions = null;

    public function getConfig($key)
    {
        return Mage::getStoreConfig($this->_configPathPrefix . $key);
    }

    /**
     * Get allowed actions e.g. sort, dir, filter etc., storing lazily
     *
     * @return array
     */
    public function getAllowedActions()
    {
        if ($this->_allowedActions === null) {
            $this->_allowedActions = $this->_retrieveAllowedActions();
        }

        return $this->_allowedActions;
    }

    /**
     * Get allowed actions from config and explode comma-seperated view
     *
     * @return array
     */
    protected function _retrieveAllowedActions()
    {
        $keys = $this->getConfig('options/grid_keys');

        return explode(',',$keys);
    }
}
