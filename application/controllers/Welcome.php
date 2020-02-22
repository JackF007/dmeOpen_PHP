<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
    function __construct(){
        parent::__construct();
        $this->load->library('rssparser');
        $this->load->helper('custom_helper');// load library

    }

    public function index()
    {
        echo is_valid_rss_url('https://results.amarujala.com/rss/masti-ki-pathshala.xml');
        $this->rssparser->set_feed_url('http://albosanmarcoargentano.asmenet.it/rss.php');  // get feed
        $this->rssparser->set_cache_life(30);                       // Set cache life time in minutes
        $rss = $this->rssparser->getFeed(6); 
        pre($rss);
    }
}
