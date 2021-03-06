<?php

	$column_width = (int)(80/count($columns));

	if(!empty($list)){
?><div class="bDiv" >
		<table class="display table table-bordered table-striped dataTable no-footer" id="flex1">
		<thead>
			<tr class=''>
				<?php foreach($columns as $column){?>
				<th width='<?php echo $column_width?>%'>
					<div class="text-left field-sorting <?php if(isset($order_by[0]) &&  $column->field_name == $order_by[0]){?><?php echo $order_by[1]?><?php }?>"
						rel='<?php echo $column->field_name?>'>
						<?php echo $column->display_as?>
					</div>
				</th>
				<?php }?>
				<?php if(!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)){?>
				<th align="left" abbr="tools" axis="col1" class="" width='20%'>
					<div class="text-left">
						<?php echo $this->l('list_actions'); ?>
					</div>
				</th>
				<?php }?>
			</tr>
		</thead>
		<tbody>
<?php foreach($list as $num_row => $row){ ?>
		<tr  <?php if($num_row % 2 == 1){?>class="erow"<?php }?>>
			<?php foreach($columns as $column){?>
			<td width='<?php echo $column_width?>%' class='<?php if(isset($order_by[0]) &&  $column->field_name == $order_by[0]){?>sorted<?php }?>'>
				<div class='text-left'><?php echo $row->{$column->field_name} != '' ? $row->{$column->field_name} : '&nbsp;' ; ?></div>
			</td>
			<?php }?>
			<?php if(!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)){?>
			<td align="left" width='20%'>
				<div class=''>
					<?php if(!$unset_delete){?>
                    	<a href='<?php echo $row->delete_url?>' title='<?php echo $this->l('list_delete')?> <?php echo $subject?>' class="delete-row" >
                    			<!--<span class='delete-icon'></span>-->
													<button class="btn btn-xs btn-danger">Delete</button>
                    	</a>
                    <?php }?>
                    <?php if(!$unset_edit){?>
						<a href='<?php echo $row->edit_url?>' title='<?php echo $this->l('list_edit')?> <?php echo $subject?>' class="edit_button"><!--<span class='edit-icon'></span>-->
							<button class="btn btn-xs btn-info">Edit</button>
						</a>
					<?php }?>
					<?php if(!$unset_read){?>
						<a href='<?php echo $row->read_url?>' title='<?php echo $this->l('list_view')?> <?php echo $subject?>' class="edit_button">
							<!--<span class='read-icon'></span>-->
							<button class="btn btn-xs btn-success">View</button>
						</a>
					<?php }?>
					<?php
					if(!empty($row->action_urls)){
						foreach($row->action_urls as $action_unique_id => $action_url){
							$action = $actions[$action_unique_id];
					?>
							<a href="<?php echo $action_url; ?>" class="edit_button" title="<?php echo $action->label?>"><?php
								if(!empty($action->image_url))
								{
									?>
									<button class="<?php echo $action->css_class; ?>"> <?php echo $action->label?> </button> 
									<!--<img src="<?php echo $action->image_url; ?>" alt="<?php echo $action->label?>" />--><?php
								}
							?></a>
					<?php }
					}
					?>
          <div class='clear'></div>
				</div>
			</td>
			<?php }?>
		</tr>
<?php } ?>
		</tbody>
		</table>
	</div>
<?php }else{?>
	<br/>
	&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $this->l('list_no_items'); ?>
	<br/>
	<br/>
<?php }?>
