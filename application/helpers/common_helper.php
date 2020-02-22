<?php

	function get_user($document) {
        if(isset($document['id']) and !empty($document['id'])){
            $this->db->where('id', $document['id']);
        }
        if(isset($document['g_id']) and !empty($document['g_id'])){
            $this->db->where('g_id', $document['g_id']);
        }
        if(isset($document['fb_id']) and !empty($document['fb_id'])){
            $this->db->where('fb_id', $document['fb_id']);
        }
        if(isset($document['email']) and !empty($document['email'])){
            $this->db->where('email', $document['email']);
        }
        if(isset($document['mobile']) and !empty($document['mobile'])){
            $this->db->where('mobile', $document['mobile']);
        }
        
        $result = $this->db->get('users')->row_array();
        //if (!empty($result)) {
            //$result['profileImage'] = base_url() . 'profileImages/' . $result['profileImage'];
        //}
        return ($result) ? $result : false;
    }