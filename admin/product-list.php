<?php
include('../lib/Settings.php');

if(isset($_GET['action']) && $_GET['action'] == 'del'){
	$sql = "DELETE FROM product WHERE id = '".$_GET['id']."' ";
	mysql_query($sql);
	header('location:product-list.php');
}

$query = "SELECT * FROM product";
$results =  getResult($query);
//echo'<pre>',print_r($results), '</pre>';
?>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
	<tr>
		<td width="15%" valign="top">
			<?php include('left-menu.php'); ?>
		</td>
		<td width="85%" align="right">
			<a href="product-addedit.php">Add New</a>
			<table width="100%" border="0" cellspacing="5" cellpadding="0">
				<tr>
					<th>ID</th>
					<th>Image</th>
					<th>Title</th>
					<th>Price</th>
					<th>Quantity</th>
					<th>Action</th>
				</tr>
				<?php foreach($results as $res){ ?>
					<tr>
						<td align="center"><?php echo($res['id']); ?></td>
						<td align="center"><img src="../uploads/product/<?php echo($res['image']); ?>" width="80" height="80"></td>
						<td align="center"><?php echo($res['name']); ?></td>
						<td align="center"><?php echo($res['price']); ?></td>
						<td align="center"><?php echo($res['quantity']); ?></td>
						<td align="center"><a href="?action=del&id=<?php echo($res['id']); ?>">Delete</a></td>
					</tr>
				<?php } ?>
			</table>
		</td>
	</tr>
</table>