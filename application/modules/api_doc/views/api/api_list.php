<?php //pre($api_all); die;   ?>
<!DOCTYPE html>
<html>
    <?php
    $this->load->view('segments/header');
    $this->load->view('segments/sidebar');
    ?>
    <style>
        .no-hover:hover{
            cursor:default;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="z-index: 1">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $title; ?></h1>
            <?php if(isset($this->session->userdata['profile']) && $this->session->userdata['profile'] == 1) { ?>
            <div class="breadcrumb"><a title="Create new API" href="<?php echo base_url('index.php/api_doc/Api/create'); ?>" class="btn btn-success"> + </a></div><br>
            <?php } ?>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr class="hello" >
                                        <th>#</th>
                                        <th>API name</th>
<!--                                        <th>Category</th>-->
                                        <th>Url</th>
                                        <?php if(!isset($opt_for_dlt)){ ?>
                                        <th><center>Action</center></th>
                                        <?php } if(isset($this->session->userdata['profile']) && $this->session->userdata['profile'] == 1) { ?>
                                        <th></th>
                                        <?php } ?>
                                </tr>
                                </thead>

                                <tbody class="menu_change">
                                    <?php $i= 0; if (!empty($api_all)) {  
                                        foreach ($api_all as $list) {
                                            $i++;
                                            ?>
                                            <tr class="record even gradeA">
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo ucfirst($list['name']); ?></td>
<!--                                                <td><?php //echo ucfirst($list['controller']); ?></td>-->
                                                <td style="overflow:hidden"><?php echo $list['url']; ?></td>
                                                <?php if(!isset($opt_for_dlt)){ ?>
                                                <td style="width:20%; text-align: center;">
                                                    <a href="<?php echo site_url('api_doc/Api/doc/').$list['id']; ?>" class="btn btn-sm btn-success userid"><i class="fa fa-eye"></i> View</a>
                                                    <?php if(isset($this->session->userdata['profile']) && $this->session->userdata['profile'] == 1) { ?>
                                                    <a href="<?php echo site_url('api_doc/Api/create/').$list['id']; ?>" class="btn btn-sm btn-warning"><i class="fa fa-edit "></i> Edit</a> 
                                                    <a href="<?php echo site_url('api_doc/Api/copy/').$list['id']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-copy "></i> Copy</a> 
                                                    <?php } ?>
                                                </td>
                                                
                                                <?php } if(isset($opt_for_dlt)){ if(isset($this->session->userdata['profile']) && $this->session->userdata['profile'] == 1) { ?>
                                                <td>
                                                    <a href="<?php echo site_url('api_doc/Api/delete_api_perma/').$list['id']; ?>" class="btn btn-sm btn-warning"><i class="fa fa-trash "></i> Delete</a> 
                                                    <a href="<?php echo site_url('api_doc/Api/change_status/').$list['id']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-check-circle "></i> Restore</a> 
                                                </td>    
                                                <?php }}  else {  ?>
                                                <?php if(isset($this->session->userdata['profile']) && $this->session->userdata['profile'] == 1) { ?>
                                                <td><a href="<?php echo site_url('api_doc/Api/change_status/').$list['id']; ?>" class="btn btn-sm btn-danger"><i class="fa fa-trash "></i></a> </td>
                                                <?php } }  ?>
                                                
                                            </tr>
                                            
                                        <?php }} ?>
                                </tbody>
                            </table>

                            <center><div id="text-center" ></div></center>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </section>                
    </div>
    <!-- /.row -->
    <div style=" width: 100%; " id="menu_pop_up"></div>
    <!-- /.content-wrapper -->

<?php $this->load->view('segments/footer'); ?>
    <script src="<?php echo base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script>
                                                        $(document).ready(function () {
                                                            $('#example2').DataTable({
                                                                //"scrollX": true
                                                                // serverSide: true,
                                                                //ajax: '/data-source'
                                                            });
                                                        });
    </script>

    <div id="getmodal">
    </div>
</body>
<style>
    .tble-width{
        width:200px !important;
        height:20px !important;
        overflow:hidden !important;
    }
</style>



<script>
    $(document).ready(function () {
        $(".prop_status").click(function () {

            if (confirm("Are you sure ?") == true) {


                var id = $(this).attr("data-id");
                var eid = btoa(id);
                $.ajax({
                    url: "<?php echo base_url('index.php/admin_panel/') ?>Properties/change_status_ajax",
                    method: 'GET',
                    data: {
                        id: eid
                    },
                    success: function (response) {  //alert(response); 
                        $("#status_" + id).empty();
                        $("#status_" + id).html(response);
                    }
                });

            }
        });
    });

    $(document).ready(function () {
        $(".sold_status").click(function () {


            if (confirm("Are you sure ?") == true) {
                var id = $(this).attr("id");
                var eid = btoa(id);
                $.ajax({
                    url: "<?php echo base_url('index.php/admin_panel/') ?>Properties/change_sold_status_ajax",
                    method: 'GET',
                    data: {
                        id: eid
                    },
                    success: function (response) { //alert(response); 
                        $("#" + id).empty();
                        $("#" + id).html(response);
                    }

                });
            }


        });
    });
</script>
</html>
<!--////////////////////////////////////
//                                    //
//      Modified by: Harish Kumar     //
//      Created On: 24feb-2018        //
//                                    //
/////////////////////////////////////-->