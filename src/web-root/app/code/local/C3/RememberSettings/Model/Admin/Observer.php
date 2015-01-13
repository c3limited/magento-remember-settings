<?php

/**
 * Class C3_RememberSettings_Model_Admin_Observer
 */
class C3_RememberSettings_Model_Admin_Observer
{
    const GRID_PREFIX = 'grid';

    /**
     * Load admin user preferences to session
     *
     * @param $observer
     */
    public function loadGridPreferences($observer)
    {
        // Get extra (preferences) data from admin user
        $adminUser = Mage::getSingleton('admin/session')->getUser();
        if ($adminUser === null)
            return;
        $extra = $this->_getUserExtra($adminUser);

        // If preferences are saved, load them into session
        if (isset($extra['session_store']) && is_array($extra['session_store'])) {
            $session = Mage::getSingleton('adminhtml/session');
            foreach ($extra['session_store'] as $key => $data) {
                $session->setData($key, $data);
            }
        }
    }

    /**
     * Save admin user preferences from session
     *
     * @param $observer
     */
    public function saveGridPreferences($observer)
    {
        // Get admin session
        $session = Mage::getSingleton('adminhtml/session');

        // Get current data from admin user
        /** @var C3_RememberSettings_Model_Admin_User $adminUser */
        $adminUser = Mage::getSingleton('admin/session')->getUser();
        if ($adminUser === null)
            return;
        $extra = $this->_getUserExtra($adminUser);
        // Clean down session_store of extra so that changes of what to store have an immediate effect
        $oldStore = $extra['session_store'];
        $extra['session_store'] = array();

        // Set any matched data from session to admin user extra
        $changed = false;
        foreach ($session->getData() as $key => $data) {
            if ($this->_isSessionKeyStorable($key)) {
                // If data exists in extra, only set if different. If data does not exist, always set
                if ((isset($oldStore[$key]) && $oldStore[$key] !== $data) || !isset($oldStore[$key])) {
                    $changed = true;
                    // If value is blank, then do not add entry
                    if ($data !== '') {
                        $extra['session_store'][$key] = $data;
                    }
                }
            } else if (isset($oldStore[$key])) {
                // If we are not going to store this, but it was stored before, we need to save the user
                $changed = true;
            }
        }

        // Save user on change
        if ($changed) {
            Mage::log('Saving admin user to store changed grid options from session');
            $adminUser->setExtra($extra);
            $adminUser->saveExtra($extra);
        }
    }

    /**
     * Get extra data from admin-user
     *
     * @param C3_RememberSettings_Model_Admin_User $adminUser
     * @return null|array
     */
    protected function _getUserExtra($adminUser)
    {
        $extra = $adminUser->getExtra();

        if (!is_array($extra)) {
            if (empty($extra)) {
                return array();
            } else {
                $extra = unserialize($extra);
            }
        }
        return $extra;
    }

    /**
     * Is this session param name one that should be stored?
     * Ensures that this is of the grid parameters that has been chosen to be stored
     *
     * @param string $key Session param name
     * @return bool
     */
    protected function _isSessionKeyStorable($key)
    {
        $allowedActions = Mage::helper('c3_remembersettings')->getAllowedActions();

        // Foreach allowed action, use to match end of session param names
        foreach ($allowedActions as $action) {
            $actionLen = strlen($action);
            $offset = 4 + $actionLen;
            if (substr_compare($key, self::GRID_PREFIX . $action,-$offset,$offset,true) === 0) {
                return true;
            }
        }

        // If did not match any allowed action, then this key is not allowed
        return false;
    }
}
