<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script src="http://tablesorter.com/jquery.tablesorter.min.js"></script>
<link rel="stylesheet" type="text/css" href="http://tablesorter.com/themes/blue/style.css" />

<script>
$(function(){
	$('#rbac_table').tablesorter({
		'headers': {
			0: {sorter: false},
			1: {sorter: false},
			2: {sorter: false},
			3: {sorter: false},
			4: {sorter: false},
			5: {sorter: false},
			6: {sorter: false}
		},
		'widgets': ['zebra'],
		'widthFixed': true
	});

	$('#rbac_table input[type="checkbox"]').after('<span class="message"></span>');
	$('#rbac_table input[type="checkbox"]').click(function(){
		$self = $(this);
		$.post('<?php echo url::site('admin/rbac/modify') ?>', {
			'role':$self.attr('role'),
			'action':$self.attr('action'),
			'expression':$self.attr('expression')
		}, function(data){
			if (data != 'ok') {
				$self.next().show().text('failed').css('color','red').delay(1000).fadeOut('slow');
			}
			else {
				$self.next().show().text('success').css('color','green').delay(1000).fadeOut('slow');
			}
		})
	});
})
</script>
<table id="rbac_table" class="tablesorter" border="0" cellpadding="0" cellspacing="1">
	<thead>
		<tr>
			<th></th>
			<th>guest</th>
			<?php foreach($roles as $role) {
				echo '<th>'.$role->name.'</th>';
			} ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach($rules as $rule => $action) {
			$rule = explode('|', $rule);
			(count($rule) == 2)?$expression = $rule[1]:$expression = '';
			echo '<tr>';
			echo '<td>'.$rule[0].'</td>';
			$checked = Rbac::match(0, $action, $expression);
			echo '<td><input action="'.$action.'" role="0" '.$checked.' expression="'.$expression.'" type="checkbox" /></td>';
			foreach($roles as $role) {
				$checked = Rbac::match($role->id, $action, $expression);
				echo '<td><input action="'.$action.'" role="'.$role->id.'" '.$checked.' expression="'.$expression.'" type="checkbox" /></td>';
			}
			echo '</tr>';
		} ?>
	</tbody>
</table>
