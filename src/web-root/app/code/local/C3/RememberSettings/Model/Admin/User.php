<?php

class C3_RememberSettings_Model_Admin_User extends Mage_Admin_Model_User
{
    /**
     * Processing data before model save
     *
     * @return Mage_Admin_Model_User
     */
    protected function _beforeSave()
    {
        $data = array(
            'firstname' => $this->getFirstname(),
            'lastname'  => $this->getLastname(),
            'email'     => $this->getEmail(),
            'modified'  => now(),
            // @modification If already serialised, store as is
            'extra'     => is_array($this->getExtra()) ? serialize($this->getExtra()) : $this->getExtra()
            // end modification
        );

        if ($this->getId() > 0) {
            $data['user_id'] = $this->getId();
        }

        if ($this->getUsername()) {
            $data['username'] = $this->getUsername();
        }

        if ($this->getNewPassword()) {
            // Change password
            $data['password'] = $this->_getEncodedPassword($this->getNewPassword());
        } elseif ($this->getPassword() && $this->getPassword() != $this->getOrigData('password')) {
            // New user password
            $data['password'] = $this->_getEncodedPassword($this->getPassword());
        }

        if (!is_null($this->getIsActive())) {
            $data['is_active'] = intval($this->getIsActive());
        }

        $this->addData($data);

        return Mage_Core_Model_Abstract::_beforeSave();
    }
}
