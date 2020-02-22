<?php

$page = (!isset($page))?"":$page;
$dashboard = '';$web_user = '';$all_user= '';$android = '';$ios = '';$instructor = '';$location = '';
$user_query = '';$setting = '';$backend_user = '';$create_backend_user='';
$backend_user_list = '';$permission_group = '';$category = '';$category_list = '';
$mobile_user = '';$mobile_user_list = '';
$syllabus = '';$create_syllabus = '';$syllabus_list = '';
$category = ''; $create_category = ''; $category_list = '';
$levels = ''; $create_level = ''; $level_list = '';
$sublevels = ''; $create_sublevel = ''; $sublevel_list = '';
$unit = ''; $create_unit = ''; $unit_list = '';
$slides = ''; $create_slides = ''; $slides_list = '';

if($page == 'dashboard') { $dashboard = 'active';}
elseif($page == 'create_backend_user') { $create_backend_user = 'active'; $setting = 'active'; $backend_user = 'active';}
elseif($page == 'backend_user_list') { $backend_user_list = 'active'; $setting = 'active'; $backend_user = 'active';}
elseif($page == 'permission_group') { $permission_group = 'active'; $setting = 'active';}
elseif($page == 'mobile_users_list') { $mobile_user_list = 'active'; $setting = 'active'; $mobile_user = 'active';}
elseif($page == 'create_syllabus') { $create_syllabus = 'active'; $setting = 'active'; $syllabus= 'active';}
elseif($page == 'syllabus_list') { $syllabus_list = 'active'; $setting = 'active';  $syllabus= 'active';}
elseif($page == 'create_category') { $create_category = 'active'; $setting = 'active'; $category= 'active';}
elseif($page == 'category_list') { $category_list = 'active'; $setting = 'active';  $category= 'active';}
elseif($page == 'create_level') { $create_level = 'active'; $setting = 'active'; $levels= 'active';}
elseif($page == 'level_list') { $level_list = 'active'; $setting = 'active';  $levels= 'active';}
elseif($page == 'create_sublevel') { $create_sublevel = 'active'; $setting = 'active'; $sublevels= 'active';}
elseif($page == 'sublevel_list') { $sublevel_list = 'active'; $setting = 'active';  $sublevels= 'active';}
elseif($page == 'create_unit') { $create_unit = 'active'; $setting = 'active'; $unit = 'active';}
elseif($page == 'unit_list') { $unit_list = 'active'; $setting = 'active';  $unit = 'active';}
elseif($page == 'create_slides') { $create_slides = 'active'; $setting = 'active'; $slides = 'active';}
elseif($page == 'slides_list') { $slides_list = 'active'; $setting = 'active';  $slides = 'active';}


$sidebar_url = array('web_user/all_user_list');
$sidebar_url[] = 'admin/index';
$sidebar_url[] = 'admin/create_backend_user';
$sidebar_url[] = 'admin/backend_user_list';
$sidebar_url[] = 'mobile_users/mobile_users_list';
$sidebar_url[] = 'syllabus/create_syllabus';
$sidebar_url[] = 'syllabus/syllabus_list';
$sidebar_url[] = 'category/create_category';
$sidebar_url[] = 'category/category_list';
$sidebar_url[] = 'level/create_level';
$sidebar_url[] = 'level/level_list';
$sidebar_url[] = 'sublevel/create_sublevel';
$sidebar_url[] = 'sublevel/sublevel_list';
$sidebar_url[] = 'unit/create_unit';
$sidebar_url[] = 'unit/unit_list';
$sidebar_url[] = 'slides/create_slides';
$sidebar_url[] = 'slides/slides_list';


if(is_array($sidebar_url))
{
    $sidebar_url = implode("','",$sidebar_url);
    $sidebar_url =  "'".$sidebar_url."'";
}
$session_userdata = $this->session->userdata();
$user_permsn =  $this->db->query("SELECT pg.permission_fk_id FROM backend_user_role_permissions as burps left join permission_group as pg on pg.id = burps.permission_group_id where burps.user_id = '".$session_userdata['active_backend_user_id']."' ")->row_array();
$user_permsn = ($user_permsn['permission_fk_id'])?$user_permsn['permission_fk_id']:'0';
$query = $this->db->query("SELECT * from backend_user_permission where id IN ($user_permsn)")->result_array();
$result_side_bar = $query;
$temp = array();
foreach ($result_side_bar as  $value)
{
    $temp[$value['permission_perm']]= $value['id'];
}
?>
<aside>
    <div id="sidebar" class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">


            <!-- ################################ DASHBOARD MENU  ############################################-->

            <?php if(array_key_exists("admin/index",$temp) && $temp['admin/index'] != '') { ?>
            <li>
                <a class="<?php echo $dashboard; ?>" href="<?php echo AUTH_PANEL_URL . 'admin/index'; ?>">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <?php } ?>

            <!-- ################################N############################################################-->

            <!-- ################################ BACKEND USERS MENU  ########################################-->

            <li class="sub-menu dcjq-parent-li">
                <a href="javascript:;" class="dcjq-parent <?php echo $backend_user; ?>">
                    <i class="fa fa-users"></i>
                    <span>Backend Users</span>
                    <span class="dcjq-icon"></span>
                </a>
                <ul class="sub" style="display: block;">
                    <?php if(array_key_exists("admin/create_backend_user",$temp) && $temp['admin/create_backend_user'] != '') { ?>
                  
                    <?php }  if(array_key_exists("admin/backend_user_list",$temp) && $temp['admin/backend_user_list'] != '') { ?>
                    <li class="<?php echo $backend_user_list; ?>"><a href="<?php echo AUTH_PANEL_URL . 'admin/backend_user_list'; ?>">View List</a></li>
                    <?php } ?>
                </ul>
            </li>

            <!-- ################################N############################################################-->

            <!-- ################################ Category  ########################################-->

            <li class="sub-menu dcjq-parent-li">
                <a href="javascript:;" class="dcjq-parent ">
                    <i class="fa fa-list"></i>
                    <span>Category</span>
                    <span class="dcjq-icon"></span>
                </a>
                <ul class="sub" style="display: block;">
                    <?php if(array_key_exists("admin/create_backend_user",$temp) && $temp['admin/create_backend_user'] != '') { ?>
                    <li class="sub-menu dcjq-parent-li"><a href="<?php echo AUTH_PANEL_URL . 'Category/categories/1'; ?>">Foreign Investment </a>
<!--                        <ul class="sub" style="display: block;">
                            <li class=""><a href="<?php echo AUTH_PANEL_URL . 'Category/create_subcategory/1'; ?>">Add New</a></li>
                            <li class=""><a >View List</a></li>
                        </ul>-->
                    </li>
                    <?php }  if(array_key_exists("admin/backend_user_list",$temp) && $temp['admin/backend_user_list'] != '') { ?>
                    <li class="<?php echo $syllabus_list; ?>"><a href="<?php echo AUTH_PANEL_URL . 'Category/categories/2'; ?>">Communications</a>
<!--                        <ul class="sub" style="display: block;">
                            <li class=""><a href="<?php echo AUTH_PANEL_URL . 'Category/create_subcategory/2'; ?>">Add New</a></li>
                            <li class=""><a >View List</a></li>
                        </ul>-->
                    </li>
                    <li  class="<?php echo $syllabus_list; ?>"><a href="<?php echo AUTH_PANEL_URL . 'Category/categories/3'; ?>">Competitions</a>
<!--                        <ul class="sub" style="display: block;">
                            <li class=""><a href="<?php echo AUTH_PANEL_URL . 'Category/create_subcategory/3'; ?>">Add New</a></li>
                            <li class=""><a href="<?php echo AUTH_PANEL_URL . 'Category/categories/3'; ?>">View List</a></li>
                        </ul>-->
                    </li>
                    <!-- <li class="<?php ///echo $syllabus_list; ?>"><a href="<?php //echo AUTH_PANEL_URL . 'Category/categories/4'; ?>">Open Data</a> -->
                      <li class="<?php echo $syllabus_list; ?>"><a href="<?php echo AUTH_PANEL_URL . 'Category/categories/4'; ?>">Open Data</a>
<!--                        <ul class="sub" style="display: block;">
                            <li class=""><a href="<?php echo AUTH_PANEL_URL . 'Category/create_subcategory/4'; ?>">Add New</a></li>
                            <li class=""><a >View List</a></li>
                        </ul>-->

                    </li>
                    <?php } ?>
                </ul>

            </li>

            <!-- ################################N############################################################-->

        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
