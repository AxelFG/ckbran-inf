<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<section>
	<title><?php echo $view->section->title ? $view->section->title : $view->section->name;?></title>
	<records>
		<?php foreach ($view->items as $item):?>
		<record>
			<id><?php echo $item->id;?></id>
			<title><?php echo $item->title;?></title>
			<createDate><?php echo $item->ctime?></createDate>
			<categories>
				<?php foreach ($item->categories as $category):?>
				<category><?php echo $category;?></category>
				<?php endforeach;?>		
			</categories>
			<type><?php echo $item->type_name?></type>
			<fields>
				<?php foreach ($item->fields_by_id as $field):?>
				<field>
					<label><?php echo $field->label?></label>
					<value><?php echo is_array($field->value) ? json_encode($field->value) : $field->value;?></value>
				</field>
				<?php endforeach;?>		
			</fields>
		</record>
		<?php endforeach;?>		
	</records>
</section>

