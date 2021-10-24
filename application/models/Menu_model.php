<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_model extends CI_Model
{

    public function getSubMenu()
    {
        $query = "Select a.*, b.menu from user_sub_menu a join user_menu b on a.menu_id = b.id";

        return $this->db->query($query)->result_array();
    }
}
