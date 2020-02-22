<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//////////////////////////////////
//                              //
//   Author : Abhishek Jaiswal  //
//   date : 22 / OCT / 2018     //
//                              //
//////////////////////////////////
class Tweetmodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function post_tweet($post) {
        $this->db->insert('post', $post);
        $res = $this->get_tweet($this->db->insert_id());
        return $res;
    }

    function get_tweet($id) {
        $url = base_url('uploads/tweet_photo/');
        $this->db->select('post.id, post.user_id, post.post, post.tag, CONCAT("' . $url . '",post.photo) as photo, post.created_date, post.update_date');
        $this->db->join('users','users.id=post.user_id');
        $this->db->where('post.id', $id);
        return $this->db->get('post')->result_array();
    }

    function get_tweets() {
        $search=$this->input->post('search');
        $url = base_url('uploads/tweet_photo/');
        $this->db->select('post.id, post.user_id, post.post, post.tag, CONCAT("' . $url . '",post.photo) as photo, post.created_date, post.update_date');
        $this->db->join('users','users.id=post.user_id');
        $this->db->where('post.status', 1);
        if($search!=''){
            $this->db->where('post like "%'.$search.'%"',NULL);
        }
        if ($this->input->post('last_post_id') != "") {
            $this->db->limit(20, $this->input->post('last_post_id'));
        } else {
            $this->db->limit(20);
        }
        $this->db->order_by('post.update_date', 'DESC');
        return $this->db->get('post')->result_array();
    }

    function get_tweet_by_user($id) {
        $url = base_url('uploads/tweet_photo/');
        $this->db->select('id, user_id, post, tag, CONCAT("' . $url . '",photo) as photo, created_date, update_date');
        $this->db->where('status', 1);
        $this->db->where('user_id', $id);
        if ($this->input->post('last_count_value') != "") {
            $this->db->limit(20, $this->input->post('last_count_value'));
        } else {
            $this->db->limit(20);
        }
        $this->db->order_by('update_date', 'DESC');
        return $this->db->get('post')->result_array();
    }

    /* Get user short data */

    function get_user_short_data($id) {
        $url = base_url('uploads/profile_photo/');
        $this->db->select('id, name, user_name, profile_picture, gender, description, dob');
        $this->db->where('id', $id);
        $res = $this->db->get('users')->row_array();
        if ($res) {
            if($res['profile_picture'] != ""){
                $res['profile_picture'] = $url.$res['profile_picture'];
            }
//            if ($res['photo'] == "") {
//                $res['profile_picture'] = "";
//                unset($res['photo']);
//            } else {
//                unset($res['photo']);
//            }
            return $res;
        }
    }

    /* End getting user short data */

    function get_tags() {
        $this->db->where('status', 1);
        $q = $this->db->get('post_tags');
        $data['res'] = $q->result_array();
        $data['count'] = $q->num_rows();
        return($data);
    }

    function get_tag($id) {
        $this->db->select('id, tag, description');
        $this->db->where('id', $id);
        $this->db->where('status', 1);
        $res = $this->db->get('post_tags')->row_array();
        return $res;
    }

    function get_array_tag($array) {
        $tag = [];
        for ($i = 0; $i < count($array); $i++) {
            $res = $this->get_tag($array[$i]);
            if ($res['tag']) {
                $tag[] = $res['tag'];
            } else {
                continue;
            }
        }
        return $tag;
    }

    function check_like($like) {
        $this->db->where('post_id', $like['post_id']);
        $this->db->where('user_id', $like['user_id']);
        return $this->db->get('user_post_like')->row_array();
    }

    function post_like($like) {
        $like['status'] = 1;
        $like['created_date'] = milliseconds();
        $like['update_date'] = milliseconds();
        $this->db->insert('user_post_like', $like);
    }

    function update_like($like, $status) {
        $this->db->where('post_id', $like['post_id']);
        $this->db->where('user_id', $like['user_id']);
        $data = array(
            'status' => $status,
            'update_date' => milliseconds()
        );
        $this->db->update('user_post_like', $data);
    }

    function count_post_like($post_id) {
        $this->db->where('post_id', $post_id);
        $this->db->where('status', 1);
        $q = $this->db->get('user_post_like');
        return $q->num_rows();
    }

    function post_comment($comment) {
        $comment['created_date'] = milliseconds();
        $comment['update_date'] = milliseconds();
        $comment['status'] = 1;
        $this->db->insert('user_post_comment', $comment);
        $res = $this->get_comment($this->db->insert_id());
        return $res;
    }

    function get_comment($id) {
        $url = base_url('uploads/comment_photo/');
        $this->db->select('id, user_id, post_id, comment, CONCAT("' . $url . '",photo) as photo, status, created_date, update_date');
        $this->db->where('id', $id);
        return $this->db->get('user_post_comment')->result_array();
    }

    function count_post_comment($post_id) {
        $this->db->join('users','users.id=user_post_comment.user_id');
        $this->db->where('user_post_comment.post_id', $post_id);
        $this->db->where('user_post_comment.status', 1);
        $q = $this->db->get('user_post_comment');
        return $q->num_rows();
    }

    function count_comment_like($comment_id) {
        $this->db->where('comment_id', $comment_id);
        $this->db->where('status', 1);
        $q = $this->db->get('user_comment_like');
        return $q->num_rows();
    }

    function get_comments($post_id) {
        $url = base_url('uploads/comment_photo/');
        $this->db->select('user_post_comment.id, user_post_comment.user_id, user_post_comment.post_id, user_post_comment.comment, CONCAT("' . $url . '",user_post_comment.photo) as photo, user_post_comment.status, user_post_comment.created_date, user_post_comment.update_date');
        $this->db->join('users','users.id=user_post_comment.user_id');
        $this->db->where('user_post_comment.post_id', $post_id);
        $this->db->where('user_post_comment.status', 1);
        if ($this->input->post('last_comment_id') != "") {
            $this->db->limit(10, $this->input->post('last_comment_id'));
        } else {
            $this->db->limit(10);
        }
        $this->db->order_by('user_post_comment.update_date', 'ASC');
        $q = $this->db->get('user_post_comment');
        if ($q)
            return $q->result_array();
    }

    function check_comment_like($like_data) {
        $this->db->where('comment_id', $like_data['comment_id']);
        $this->db->where('user_id', $like_data['user_id']);
        return $this->db->get('user_comment_like')->row_array();
    }
    
    function like_comment($like_data) {
        $like_data['status'] = 1;
        $like_data['created_date'] = milliseconds();
        $like_data['update_date'] = milliseconds();
        $this->db->insert('user_comment_like', $like_data);
    }

    function update_comment_like($like_data, $status) {
        $this->db->where('comment_id', $like_data['comment_id']);
        $this->db->where('user_id', $like_data['user_id']);
        $data = array(
            'status' => $status,
            'update_date' => milliseconds()
        );
        $this->db->update('user_comment_like', $data);
    }

    /* Start Search by post with tag */
    function search_post_by_tag_name($tag){
        $this->db->where('tag', $tag);
        $r = $this->db->get('post_tags')->row_array();
        return $this->search_post_by_tag($r['id']);
    }
    
    function get_tag_desc($tag){
        $this->db->select('id, tag, description');
        $this->db->where('tag', $tag);
        $this->db->where('status', 1);
        $r = $this->db->get('post_tags')->row_array();
        return $r;
    }
    
    function search_post_by_tag($tag_id) {
        $where = "FIND_IN_SET('" . $tag_id . "', tag)";
        $this->db->where($where);
        if ($this->input->post('last_count_value') != "") {
            $this->db->limit(20, $this->input->post('last_count_value'));
        } else {
            $this->db->limit(20);
        }
        $this->db->order_by('update_date', 'DESC');
        return $this->db->get('post')->result_array();
    }

    /* End Search by post with tag */

    function get_user_profile($id) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('post', 'users.id = post.user_id', 'right');
        $this->db->where('users.id', $id);
        return $this->db->get()->result_array();
    }

    function get_single_post($post_id) {
        $url = base_url('uploads/tweet_photo/');
        $this->db->select('id, user_id, post, tag, CONCAT("' . $url . '",photo) as photo, created_date, update_date');
        $this->db->where('id', $post_id);
        return $this->db->get('post')->row_array();
    }
    
    function check_like_by_user($user_id, $post_id){
        $this->db->select('status');
        $this->db->where('post_id', $post_id);
        $this->db->where('user_id', $user_id);
        $q = $this->db->get('user_post_like');
        return $q->row_array();
    }
    
    function check_comment_like_by_user($user_id, $comment_id){
        $this->db->select('status');
        $this->db->where('comment_id', $comment_id);
        $this->db->where('user_id', $user_id);
        $q = $this->db->get('user_comment_like');
        return $q->row_array();
    }

}
