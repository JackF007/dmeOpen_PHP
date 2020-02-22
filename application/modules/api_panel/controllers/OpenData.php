<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//////////////////////////////////
//                              //
//   Author : Abhishek Jaiswal  //
//   Mode : 22 / Oct /2018      //
//                              //                            
//////////////////////////////////


class OpenData extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper("aul");
        $this->load->helper("array");
        $this->load->library("form_validation");
        $this->load->model("OpenDataModel");
        $this->load->helper("services");
        $this->load->helper("image_upload_helper");
        $this->load->library("session");
        $this->load->helper("check_image");
    }

    /* start getting open data tweets */

    function get_tweets() {
        $input = $this->validate_get_tweets();
        $res = $this->OpenDataModel->get_tweets();
        if ($res) {
            foreach ($res as $r) {
                $data[] = array(
                    'id' => $r['id'],
                    'post' => $r['post'],
                    'tag' => $this->OpenDataModel->get_array_tag(explode(',', $r['tag'])),
                    'photo' => $this->check_image($r['photo']), //$r['photo'],
                    'likes' => $this->OpenDataModel->count_post_like($r['id']),
                    'comments' => $this->OpenDataModel->count_post_comment($r['id']),
                    'created_date' => $r['created_date'],
                    'update_date' => $r['update_date'],
                    'is_liked' => $this->check_like_by_user($input['user_id'], $r['id'])
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

    /* start like post */

    function post_like() {
        $inputs = $this->validate_post_like();
        $res = $this->OpenDataModel->check_like($inputs);
        if ($res['status'] == 1) {
            //dislike
            $this->OpenDataModel->update_like($inputs, 2);
            $data = array('status' => true, 'message' => 'Unliked', 'liked_status' => 0);
            echo json_encode($data); die;
        } elseif ($res['status'] == 2) {
            //like
            $this->OpenDataModel->update_like($inputs, 1);
            $data = array('status' => true, 'message' => 'Liked', 'liked_status' => 1);
            echo json_encode($data); die;
        } else {
            //add to like
            $this->OpenDataModel->post_like($inputs);
            $data = array('status' => true, 'message' => 'Liked', 'liked_status' => 1);
            echo json_encode($data); die;
        }
        return_data(false, 'Error! while liking post');
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

    /* end like post */

    /* start post comment */

    function post_comment() {
        $inputs = $this->validate_post_comment();
        $inputs['photo'] = "";
        if (!empty($_FILES['photo']['name'])) {
            $image_data = array(
                'key' => 'photo',
                'upload_path' => './uploads/open_data/'
            );
            $inputs['photo'] = upload_image($image_data);
        }
        $res = $this->OpenDataModel->post_comment($inputs);
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

    /* start like comment */

    function comment_like() {
        $inputs = $this->validate_comment_like();
        //pre($inputs);die;
        $res = $this->OpenDataModel->check_comment_like($inputs);
        //pre($res);die;
        //if(!empty($res)){
            if ($res['status'] == 1) {
                //dislike
                $this->OpenDataModel->update_comment_like($inputs, 2);
                $data = array('status' => true, 'message' => 'Unliked', 'liked_status' => 0);
                echo json_encode($data); die;
            } elseif ($res['status'] == 2) {
                //like
                $this->OpenDataModel->update_comment_like($inputs, 1);
                $data = array('status' => true, 'message' => 'Liked', 'liked_status' => 1);
                echo json_encode($data); die;
            } else {
                //add to like
                $this->OpenDataModel->like_comment($inputs);
                $data = array('status' => true, 'message' => 'Liked', 'liked_status' => 1);
                echo json_encode($data); die;
            }
        //}else{
           // $this->db->insert('admin_comment_like',array('comment_id'=>$inputs['comment_id'],'user_id'=>$inputs['user_id'],'created_date'=>milliseconds(),'status' => $status,
           // 'update_date' => milliseconds()));
          //  $data = array('status' => true, 'message' => 'Liked', 'liked_status' => 1);
         //   echo json_encode($data); die;
       // }
        return_data(false, 'Error! while liking comment.');
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

    /* end like comment */

    /* start search post by tag */

    public function search_post_by_tag() {
        $data = [];
        $var='{}';
        $nodata= json_decode($var);
        $input = $this->validate_search_post_by_tag();
        $post = $this->OpenDataModel->search_post_by_tag($input['tag_id']);
        if ($post) {
            $tag_detail = $this->OpenDataModel->get_tag($input['tag_id']);
            foreach ($post as $r) {
                $data[] = array(
                    'post_detail' => array(
                        'id' => $r['id'],
                        'post' => $r['post'],
                        'tag' => $this->OpenDataModel->get_array_tag(explode(',', $r['tag'])),
                        'photo' => $this->check_image($r['photo']), //$r['photo'],
                        'likes' => $this->OpenDataModel->count_post_like($r['id']),
                        'comments' => $this->OpenDataModel->count_post_comment($r['id']),
                        'created_date' => $r['created_date'],
                        'update_date' => $r['update_date'],
                        'is_liked' => $this->check_like_by_user($input['user_id'], $r['id'])
                    ),
                );
            }
            $d['tag_detail'] = $tag_detail;
            $d['posts'] = $data;
            return_data(true, 'Success! Post found.', $d);
        }
        return_data(false, 'No Post Found in this tag.',$nodata);
    }

    private function validate_search_post_by_tag() {
        $this->form_validation->set_rules('tag_id', 'Tag Id', 'required|numeric');
        $this->form_validation->set_rules('last_count_value', 'Last count value', 'trim|numeric');
        $this->form_validation->set_rules('user_id', 'user_id', 'trim|required');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], array(), $error);
        }
        return $this->input->post();
    }

    /* end search post by tag */

    /* Start Get Single Post */

    function get_single_post() {
        $inputs = $this->validate_get_single_post();
        $post = $this->OpenDataModel->get_single_post($inputs['post_id']);
        if ($post) {
            $data = array(
                'post_detail' => array(
                    'id' => $post['id'],
                    'post' => $post['post'],
                    'tag' => $this->OpenDataModel->get_array_tag(explode(',', $post['tag'])),
                    'photo' => $this->check_image($post['photo']), //$post['photo'],
                    'likes' => $this->OpenDataModel->count_post_like($post['id']),
                    'comments' => $this->OpenDataModel->count_post_comment($post['id']),
                    'created_date' => $post['created_date'],
                    'update_date' => $post['update_date'],
                    'is_liked' => $this->check_like_by_user($inputs['user_id'], $post['id']),
                    'files'=>$this->OpenDataModel->get_post_files($post['id'])
                ),
                'comments' => $this->get_comments($post['id'], $inputs['user_id'])
            );
            return_data(true, 'Success!', $data);
        }
        return_data(false, 'Nothing Found!');
    }

    private function validate_get_single_post() {
        $this->form_validation->set_rules('post_id', 'Post Id', 'trim|required');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], array(), $error);
        }
        return $this->input->post();
    }

    private function get_comments($id, $user_id) {
        $data = [];
        $res = $this->OpenDataModel->get_comments($id);
        if ($res) {
            //pre($res);die;
            foreach ($res as $r) {
                $data[] = array(
                    'comment_id' => $r['id'],
                    'post_id' => $r['post_id'],
                    'comment' => $r['comment'],
                    'photo' => $this->check_image($r['photo']), //$r['photo'],
                    'likes' => $this->OpenDataModel->count_comment_like($r['id']),
                    'created_date' => $r['created_date'],
                    'update_date' => $r['update_date'],
                    'is_liked' => $this->check_comment_like_by_user($user_id, $r['id']),
                    'user_detail' => $this->OpenDataModel->get_user_short_data($r['user_id'])
                );
            }
        }
        return $data;
    }

    private function check_image($img) {
        if (remote_file_exists($img, 1)) {
            return $img;
        } else {
            return "";
        }
    }

    /* end get single post */
    
    private function check_like_by_user($user_id, $post_id){
        $like = $this->OpenDataModel->check_like_by_user($user_id, $post_id);
        if($like['status'] == 1){
            return 1;
        }else{
            return 0;
        }
    }
    
    private function check_comment_like_by_user($user_id, $comment_id){
        $like = $this->OpenDataModel->check_comment_like_by_user($user_id, $comment_id);
        if($like['status'] == 1){
            return 1;
        }else{
            return 0;
        }
    }
    //complete checked
    //22-oct-18
}