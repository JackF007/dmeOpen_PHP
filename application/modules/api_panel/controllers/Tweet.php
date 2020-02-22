<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//////////////////////////////////
//                              //
//   Author : Abhishek Jaiswal  //
//   date : 11 / OCT / 2018     //
//   Mod : 1 / NOV / 2018      //
//                              //
//////////////////////////////////


class Tweet extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper("aul");
        $this->load->helper("array");
        $this->load->library("form_validation");
        $this->load->model("TweetModel");
        $this->load->helper("services");
        $this->load->helper("image_upload_helper");
        $this->load->helper("check_image");
        $this->load->library("session");
    }

    /* start post tweet */

    function post_tweet() {
        $inputs = $this->validate_post_tweet();
        $inputs['created_date'] = milliseconds();
        $inputs['update_date'] = milliseconds();
        $inputs['status'] = 1;
        $inputs['photo'] = "";
        if (!empty($_FILES['photo']['name'])) {
            $image_data = array(
                'key' => 'photo',
                'upload_path' => './uploads/tweet_photo/'
            );
            $inputs['photo'] = upload_image($image_data);
        }

        $res = $this->TweetModel->post_tweet($inputs);
        if ($res) {
            //$res['likes'] = 0;
            return_data(true, 'Tweet Successfully posted!', $res);
        }
        return_data(false, 'Error! While posting tweet!', array());
    }

    private function validate_post_tweet() {
        post_check();
        $this->form_validation->set_rules('post', 'Post', 'trim');
        $this->form_validation->set_rules('tag', 'Hashtag', 'trim');
        $this->form_validation->set_rules('user_id', 'User Id', 'required');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], array(), $error);
        }
        return $this->input->post();
    }

    /* End Post tweet */

    /* Start Post tweet */

    function get_tweets() {
        $input = $this->validate_get_tweets();
        $res = $this->TweetModel->get_tweets();
        if ($res) {
            foreach ($res as $r) {
                $data[] = array(
                    'post_detail' => array(
                        'id' => $r['id'],
                        'post' => $r['post'],
                        'tag' => $this->TweetModel->get_array_tag(explode(',', $r['tag'])),
                        'photo' => $this->check_image($r['photo']), //$r['photo'],
                        'likes' => $this->TweetModel->count_post_like($r['id']),
                        'comments' => $this->TweetModel->count_post_comment($r['id']),
                        'created_date' => $r['created_date'],
                        'update_date' => $r['update_date'],
                        'is_liked' => $this->check_like_by_user($input['user_id'], $r['id'])
                    ),
                    'user_detail' => $this->TweetModel->get_user_short_data($r['user_id'])
                );
            }

            if ($data) {
                return_data(true, 'Successfully! Found All Tweets.', $data);
            }
        }
        return_data(false, 'No tweet found.', array());
    }
    
    private function validate_get_tweets(){
        post_check();
        $this->form_validation->set_rules('user_id', 'user_id', 'trim|required');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], array(), $error);
        }
        return $this->input->post();
    }

    /* End Post Tweet */

    /* Start get all tags */

    function tags() {
        $res = $this->TweetModel->get_tags();
        if ($res['res']) {
            return_data(true, $res['count'] . ' Tags found', $res['res']);
        }
        return_data(false, 'No tags found', array());
    }

    /* end tags */

    /* Start post like */

    function post_like() {
        $inputs = $this->validate_post_like();
        $res = $this->TweetModel->check_like($inputs);
        if ($res['status'] == 1) {
            //dislike
            $this->TweetModel->update_like($inputs, 2);
            $data = array('status' => true, 'message' => 'Unliked', 'liked_status' => 0);
            echo json_encode($data); die;
        } elseif ($res['status'] == 2) {
            //like
            $this->TweetModel->update_like($inputs, 1);
            $data = array('status' => true, 'message' => 'Liked', 'liked_status' => 1);
            echo json_encode($data); die;
        } else {
            //add to like
            $this->TweetModel->post_like($inputs);
            $data = array('status' => true, 'message' => 'Liked', 'liked_status' => 1);
            echo json_encode($data); die;
        }
    }

    private function validate_post_like() {
        post_check();
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        $this->form_validation->set_rules('post_id', 'Post Id', 'trim|required');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], array(), $error);
        }
        return $this->input->post();
    }

    /* End start post like */

    /* Start post comment */

    function post_comment() {
        $inputs = $this->validate_post_comment();
        $inputs['photo'] = "";
        if (!empty($_FILES['photo']['name'])) {
            $image_data = array(
                'key' => 'photo',
                'upload_path' => './uploads/comment_photo/'
            );
            $inputs['photo'] = upload_image($image_data);
        }
        $res = $this->TweetModel->post_comment($inputs);
        if ($res) {
            return_data(true, 'Success! Commented.', $res);
        }
        return_data(false, 'Error! while commenting', array());
    }

    private function validate_post_comment() {
        post_check();
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        $this->form_validation->set_rules('post_id', 'Post Id', 'trim|required');
        $this->form_validation->set_rules('comment', 'Comment', 'trim');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], array(), $error);
        }
        return $this->input->post();
    }

    /* end post comment */

    /* Start get comment */

    function get_comments($id, $user_id) {
        $data = [];
        //$input = $this->validate_get_comments();
        $res = $this->TweetModel->get_comments($id);
        if ($res) {
            foreach ($res as $r) {
                $data[] = array(
                    'comment_id' => $r['id'],
                    'post_id' => $r['post_id'],
                    'comment' => $r['comment'],
                    'photo' => $this->check_image($r['photo']),
                    'likes' => $this->TweetModel->count_comment_like($r['id']),
                    'created_date' => $r['created_date'],
                    'update_date' => $r['update_date'],
                    'is_liked' => $this->check_comment_like_by_user($user_id, $r['id']),
                    'user_detail' => $this->TweetModel->get_user_short_data($r['user_id'])
                    
                );
            }
        }
        return $data;
        //return_data(false, 'No Comment Found', array());
    }

    /* End get comment */

//    private function validate_get_comments() {
//        post_check();
//        $this->form_validation->set_rules('post_id', 'Post Id', 'trim|required');
//        if ($this->input->post('last_comment_id') != "") {
//            $this->form_validation->set_rules('last_comment_id', 'Last Comment Id', 'trim|required');
//        }
//        $this->form_validation->run();
//        $error = $this->form_validation->get_all_errors();
//        if ($error) {
//            return_data(false, array_values($error)[0], array(), $error);
//        }
//        return $this->input->post();
//    }

    /* Start comment like */
    function comment_like() {
        $inputs = $this->validate_comment_like();
        $res = $this->TweetModel->check_comment_like($inputs);
        if ($res['status'] == 1) {
            //dislike
            $this->TweetModel->update_comment_like($inputs, 2);
            $data = array('status' => true, 'message' => 'Unliked', 'liked_status' => 0);
            echo json_encode($data); die;
        } elseif ($res['status'] == 2) {
            //like
            $this->TweetModel->update_comment_like($inputs, 1);
            $data = array('status' => true, 'message' => 'Liked', 'liked_status' => 1);
            echo json_encode($data); die;
        } else {
            //add to like
            $this->TweetModel->like_comment($inputs);
            $data = array('status' => true, 'message' => 'Liked', 'liked_status' => 1);
            echo json_encode($data); die;
        }
    }

    private function validate_comment_like() {
        post_check();
        $this->form_validation->set_rules('comment_id', 'Comment Id', 'trim|required');
        $this->form_validation->set_rules('user_id', 'Post Id', 'trim|required');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], array(), $error);
        }
        return $this->input->post();
    }

    /* End comment like */

    /* Start search post by tag */

    public function search_post_by_tag() {
        $data = [];
        $input = $this->validate_search_post_by_tag();
        //$post = $this->TweetModel->search_post_by_tag($input['tag_id']);
        $post = $this->TweetModel->search_post_by_tag_name($input['tag']);
        if ($post) {
            $tag_detail = $this->TweetModel->get_tag_desc($input['tag']);
            foreach ($post as $r) {
                $data[] = array(
                    'post_detail' => array(
                        'id' => $r['id'],
                        'post' => $r['post'],
                        'tag' => $this->TweetModel->get_array_tag(explode(',', $r['tag'])),
                        'photo' => $this->check_image($r['photo']), //$r['photo'],
                        'likes' => $this->TweetModel->count_post_like($r['id']),
                        'comments' => $this->TweetModel->count_post_comment($r['id']),
                        'created_date' => $r['created_date'],
                        'update_date' => $r['update_date'],
                        'is_liked' => $this->check_like_by_user($input['user_id'], $r['id'])
                    ),
                    'user_detail' => $this->TweetModel->get_user_short_data($r['user_id'])
                );
            }
            $d['tag_detail'] = $tag_detail;
            $d['posts'] = $data;
            return_data(true, 'Success! Post found.', $d);
        }
        return_data(false, 'No Post Found in this tag.', blank_json());
    }

    private function validate_search_post_by_tag() {
        post_check();
        $this->form_validation->set_rules('tag', 'Tag', 'required');
        $this->form_validation->set_rules('last_count', 'Last count value', 'trim|required');
        $this->form_validation->set_rules('user_id', 'user_id', 'trim|required');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], blank_json(), $error);
        }
        return $this->input->post();
    }

    /* End search post by tag */

    /* Start get user profile */

    function get_user_profile() {
        $data = [];
        $input = $this->validate_user_profile();
        $res = $this->TweetModel->get_user_short_data($input['user_id']);
        if ($res) {
            $post = $this->TweetModel->get_tweet_by_user($res['id']);
            if ($post) {
                foreach ($post as $r) {
                    $data[] = array(
                        'post_detail' => array(
                            'id' => $r['id'],
                            'post' => $r['post'],
                            'tag' => $this->TweetModel->get_array_tag(explode(',', $r['tag'])),
                            'photo' => $this->check_image($r['photo']),
                            'likes' => $this->TweetModel->count_post_like($r['id']),
                            'comments' => $this->TweetModel->count_post_comment($r['id']),
                            'created_date' => $r['created_date'],
                            'update_date' => $r['update_date'],
                            'is_liked' => $this->check_like_by_user($input['user_id'], $r['id'])
                        )
                    );
                }
            }
//            if ($res['photo'] == "") {
//                $res['profile_picture'] = "";
//            }
            $d['user_data'] = $res;
            $d['posts'] = $data;
            return_data(true, 'Success!', $d);
        }
        return_data(false, 'Nothing Found!');
    }

    private function validate_user_profile() {
        post_check();
        $this->form_validation->set_rules('user_id', 'User Id', 'required|trim');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], array(), $error);
        }
        return $this->input->post();
    }

    /* End get user profile */

    /* Start get single post detail */

    function get_single_post() {
        $inputs = $this->validate_get_single_post();
        $post = $this->TweetModel->get_single_post($inputs['post_id']);
        if ($post) {
            $data = array(
                'user_detail' => $this->TweetModel->get_user_short_data($post['user_id']),
                'post_detail' => array(
                    'id' => $post['id'],
                    'post' => $post['post'],
                    'tag' => $this->TweetModel->get_array_tag(explode(',', $post['tag'])),
                    'photo' => $this->check_image($post['photo']), //$post['photo'],
                    'likes' => $this->TweetModel->count_post_like($post['id']),
                    'comments' => $this->TweetModel->count_post_comment($post['id']),
                    'created_date' => $post['created_date'],
                    'update_date' => $post['update_date'],
                    'is_liked' => $this->check_like_by_user($inputs['user_id'], $post['id'])
                ),
                'comments' => $this->get_comments($post['id'], $inputs['user_id'])
            );
            return_data(true, 'Success!', $data);
        }
        return_data(false, 'Nothing Found', blank_json());
    }

    private function validate_get_single_post() {
        post_check();
        $this->form_validation->set_rules('post_id', 'Post Id', 'trim|required');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], array(), $error);
        }
        return $this->input->post();
    }

    /* End get single post */

    /* Check Image */

    private function check_image($img) {
        if (remote_file_exists($img, 1)) {
            return $img;
        } else {
            return "";
        }
    }
    
    private function check_like_by_user($user_id, $post_id){
        $like = $this->TweetModel->check_like_by_user($user_id, $post_id);
        if($like['status'] == 1){
            return 1;
        }else{
            return 0;
        }
    }
    
    private function check_comment_like_by_user($user_id, $comment_id){
        $like = $this->TweetModel->check_comment_like_by_user($user_id, $comment_id);
        if($like['status'] == 1){
            return 1;
        }else{
            return 0;
        }
    }
    
    function chk(){
        $false = "o";
        $a = "false";
        echo $$a;
    }
}
