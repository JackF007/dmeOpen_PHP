<?php
$sql = "SELECT count(`id`) as total  FROM `syllabus` where status = 0" ;
//$total_syllabus =  $this->db->query($sql)->row()->total;
$sql = "SELECT count(`id`) as total  FROM `category` where status=0 " ;
//$total_category =  $this->db->query($sql)->row()->total;
$sql = "SELECT count(`id`) as total  FROM `level` where status=0" ;
//$total_level =  $this->db->query($sql)->row()->total;
$sql = "SELECT count(`id`) as total  FROM `sub_level` where status=0" ;
//$total_sublevel =  $this->db->query($sql)->row()->total;
$sql = "SELECT count(`id`) as total  FROM `units` where status=0" ;
//$total_units =  $this->db->query($sql)->row()->total;
// $sql = "SELECT count(`id`) as total  FROM `news` where status=0 " ;
// $total_news =  $this->db->query($sql)->row()->total;
?>
<div class="col-md-12">
  <h2 class="text-center">WELCOME to <span style="color:#39b5aa;">demOpen</span>  <span style="color:#FF6C60;">ADMIN</span> </h2>
  <br>
</div>
  <div class="clearfix"></div>
  <div class=" state-overview">
                  <div class="col-lg-3 col-sm-6">
                    <a href="<?php echo site_url()."/auth_panel/admin/backend_user_list"?>">
                      <section class="panel">
                          <div class="symbol terques">
                              <i class="fa fa-book"></i>
                          </div>
                          <div class="value">

                              <h1 class="count"><?php echo $user_count; ?></h1>
                              <p>Total Users</p>
                          </div>
                      </section>
                    </a>
                  </div>
                  <div class="col-lg-3 col-sm-6">
                    <!-- <a href="<?php// echo site_url()."/auth_panel/Category/categories/1"?>"> -->
                       <a href="javascript:void(0)">
                      <section class="panel" style="cursor:default;">
                          <div class="symbol red">
                              <i class="fa fa-list"></i>
                          </div>
                          <div class="value">
                              <h1 class="count2"><?php  echo $total_category; ?></h1>
                              <p>Total Category</p>
                          </div>
                      </section>
                    </a>
                  </div>
                   <div class="col-lg-3 col-sm-6" >
                       <!-- <a href="<?php// echo site_url()."/auth_panel/admin/Category/categories/2"?>"> -->
                        <a href="javascript:void(0)">
                      <section class="panel" style="cursor:default;">
                          <div class="symbol" style="background-color: #01a89e;" >
                              <i class="fa fa-level-down"></i>
                          </div>
                          <div class="value">
                              <h1 class=" count2"><?php  echo $total_subcategory; ?></h1>
                              <p>Total SubCategory</p>
                          </div>
                      </section>
                        </a>
                  </div>
                  <!-- <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol yellow">
                              <i class="fa fa-level-down"></i>
                          </div>
                          <div class="value">
                              <h1 class=" count3"><?php  //echo $total_sublevel; ?></h1>
                              <p>Total Sub Level</p>
                          </div>
                      </section>
                  </div> -->
                  <!-- <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol blue">
                              <i class="fa fa-list-alt custom"></i>
                          </div>
                          <div class="value">
                              <h1 class=" count4"><?php //echo $total_units; ?></h1>
                              <p>Total Units</p>
                          </div>
                      </section>
                  </div> -->
                  <!-- <div class="col-lg-3 col-sm-6">
                    <section class="panel">
                        <div class="symbol" style="background-color:#8175c7">
                            <i class="fa fa-edit"></i>
                        </div>
                        <div class="value">
                            <h1 class=" count4"><?php  //echo $total_news; ?></h1>
                            <p>Total News</p>
                        </div>
                    </section>
                  </div> -->
              </div>
