C3 Remember Admin Settings Extension
====================================

Stores preferences for displaying admin grids against admin users

Features
--------
* Ensures that grid preferences are not lost between admin sessions
* Fixes Magento bug that stops users preferences for the display of system config tabs from
  being correctly saved
* Works across all grids to save subset of the standard parameters of:
    ** Sort Column
    ** Sort Direction
    ** Search Filters
    ** Rows per Page
    ** Current Page Number
* Admin interface to select which of these settings get saved
* Uses controller_action_predispatch_adminhtml and controller_action_predispatch_adminhtml to
  load grid preferences from admin user to session and save them back again
