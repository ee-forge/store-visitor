<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Update Store orders that should be owned by newly registered email address.
 *
 * @package             Store-to-Visitor
 * @author              Ron Hickson (ron@ee-forge.com)
 * @copyright           Copyright (c) 2015 EE-Forge
 * @license             http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @link                http://ee-forge.com
 */

class Store_visitor_ext {

    public $EE;
    public $name            = 'Store-to-Visitor';
    public $version         = '0.9';
    public $description     = 'Connect new Zoo Visitor registrations to previously placed orders';
    public $docs_url        = '';
    public $settings        = array();
    public $settings_exist  = 'n';

    // ------------------------------------------------------

    /**
     * Constructor
     *
     * @param   mixed   Settings array or empty string if none exist
     * @return void
     */
    public function __construct($settings = array())
    {
        $this->EE =& get_instance();
        $this->settings = $settings;
    }

    // ------------------------------------------------------

    /**
     * Activate Extension
     *
     * @return void
     */
    public function activate_extension()
    {
        $data = array(
            'class'     => __CLASS__,
            'method'    => 'link_orders',
            'hook'      => 'zoo_visitor_register_end',
            'priority'  => 5,
            'version'   => $this->version,
            'enabled'   => 'y'
        );

        ee()->db->insert('extensions', $data);
    }

    // ------------------------------------------------------

    /**
     * Disable Extension
     *
     * @return void
     */
    public function disable_extension()
    {
        $this->EE->db->where('class', __CLASS__);
        $this->EE->db->delete('extensions');
    }

    // ------------------------------------------------------

    /**
     * Update Extension
     *
     * @param   string  String value of current version
     * @return  mixed   void on update / FALSE if none
     */
    public function update_extension($current = '')
    {
        if ($current == '' OR (version_compare($current, $this->version) === 0))
        {
            return FALSE; // up to date
        }
    }

    // --------------------------------------------------------------------

    /**
     * Link Orders
     *
     * Updates Store orders with the new member id based on matching email address
     *
     * @access     private
     * @param      string
     * @param      integer
     * @return     void
     */
    public function link_orders($member_data, $member_id) {
        ee()->db->where('order_email', $member_data['email']);
        ee()->db->update('store_orders', array('member_id' => $member_id));
    }
}

/* End of file ext.store_visitor.php */
/* Location: ./system/expressionengine/third_party/store_visitor/ext.store_visitor.php */