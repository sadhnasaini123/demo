<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        // Load database if not autoloaded
        $this->load->database();
    }

    // Existing methods...

    /**
     * Get username by referred ID
     *
     * @param int $referred_by
     * @return string
     */
    public function get_username_by_referred_by($referred_by) {
        $this->db->select('username');
        $this->db->from('users');
        $this->db->where('id', $referred_by);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->row()->username;
        }
        return 'Unknown';
    }
    
    // Existing methods...
}