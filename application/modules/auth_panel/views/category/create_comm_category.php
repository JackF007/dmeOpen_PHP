
<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading">
            <?php echo $title; ?>
            <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
                <!--<a href="javascript:;" class="fa fa-times"></a>-->
            </span>
        </header>
        <div class="panel-body">
            <form method="post" enctype="multipart/form-data">
                <?php if (isset($result['id'])) { ?>
                    <input type="hidden" value="<?php echo $result['id']; ?>" name="id">
    <!--                    <input type="hidden" value="<?php //echo $result['category_id'];  ?>" name="category_id_string">-->
                <?php } ?>
                <?php if (isset($result['id'])) { ?>
                    <input type="hidden" value="edit" name="edit">
                <?php } ?>
                <div class="form-group">
                    <label for="exampleInputEmail1">Select Parent category</label>
                    <select class="form-control" id="parent_cat" name="category_id" placeholder="Select Category Name">
                        <option value="0">None</option>
                        <?php
                        $this->db->order_by('category_name', 'ASC');
                        $categories = $this->db->get_where($table, ['status' => 0])->result_array();
                        if ($categories) {
                            foreach ($categories as $each) {
                                $selected = "";
                                if (isset($result['category_id'])) {
                                    $catid = explode(',', $result['category_id']);
                                    $last_cat_id = end($catid);
                                    if ($last_cat_id == $each['id']) {
                                        $selected = "selected";
                                    }
                                }
                                ?>
                                <option <?php if(isset($result['category_id']) == $each['id']){echo 'selected';} ?> value="<?= $each['id'] ?>"><?= ucfirst($each['category_name']) ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                    <span class="text-danger"><?php echo form_error('category_id'); ?></span>
                    <span id="cat_hierarachy"></span>
                </div>
                <div class="form-group">
                    <label for="category_name">Name</label>
                    <input type="text" value="<?php
                    if (isset($result['category_name'])) {
                        echo $result['category_name'];
                    }
                    ?>" class="form-control" id="category_name" name="category_name" placeholder="Enter Category Name">
                    <span class="text-danger"><?php echo form_error('category_name'); ?></span>
                </div>
<!--                <div class="form-group">
                    <label for="rss_link">RSS Link</label>
                    <input type="text" value="<?php
                    if (isset($result['rss_link'])) {
                        echo $result['rss_link'];
                    }
                    ?>" class="form-control" id="rss_link" name="rss_link" placeholder="Enter Rss link">
                    <span class="text-danger"><?php echo form_error('rss_link'); ?></span>
                </div>-->

                <div class="form-group">
                    <label for="logo">Image</label>
                    <input type="file"  id="logo" name="logo" onchange="return readURL(this)" value="<?php
                    if (isset($result['rss_link'])) {
                        echo $result['logo'];
                    }
                    ?>">
                    <span class="text-danger"><?php echo form_error('logo'); ?></span>
                    <br>
                    <figure class="upload_img" style="display: none;">
                        <img src="" id="logo-img-tag" width="100px" height="100px"/></figure>
                </div>
                <div  class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </section>
</div>


<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>

<script type="text/javascript">
                        /* Image Upload */

                        function readURL(input) {
                            var url = input.value;
                            var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                            if (input.files && input.files[0] && (ext == "png" || ext == "jpeg" || ext == "jpg")) {
                                $('.upload_img').css({"display": "block"});
                                var reader = new FileReader();
                                reader.onload = function (e) {

                                    $('#logo-img-tag').attr('src', e.target.result);
                                }
                                reader.readAsDataURL(input.files[0]);
                            }
                        }

</script>
<script>
    $('#parent_cat').change(function () {
        jQuery.ajax({
            url: "<?php echo base_url('index.php/auth_panel/category/ajax_get_cat_hierarchy'); ?>",
            method: 'POST',
            data: {
                cat_id: $(this).val(),
            },
            success: function (data) { //alert(data);
                $('#cat_hierarachy').html(data);
            }
        });

    });
</script>







