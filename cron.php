<?php
ini_set('memory_limit', '1024M');
error_reporting(E_ALL);

try {
    $db1 = new PDO('mysql:host=localhost;dbname=shopdev', 'root', 'girnar');
} catch (PDOException $e) {
    print "shopdev databse connection error : " . $e->getMessage() . "<br/>";
    die();
}

try {
    $db2 = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', 'girnar');
} catch (PDOException $e) {
    print "ecommerce databse connection error : " . $e->getMessage() . "<br/>";
    die();
}

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

if($action == 'category'){
	$query1 = $db1->prepare('SELECT * FROM ps_category AS c 
		JOIN ps_category_lang AS cl ON cl.id_category = c.id_category
		WHERE cl.id_shop = 1 AND cl.id_lang = 1 AND cl.id_category != 1 AND cl.id_category != 2');
	$query1->execute();
	$categories = $query1->fetchAll(PDO::FETCH_ASSOC);
	//echo'<pre>',print_r($categories),'</pre>';

	foreach ($categories as $cat) {
		$sqlInsert1 = "INSERT INTO category(id, id_parent, name, date_add) VALUES (:id, :id_parent, :name, :date_add)";
		$stmt1 = $db2->prepare($sqlInsert1);                                          
		$stmt1->bindParam(':id', $cat['id_category'], PDO::PARAM_STR);       
		$stmt1->bindParam(':id_parent', $cat['id_parent'], PDO::PARAM_STR);       
		$stmt1->bindParam(':name', $cat['name'], PDO::PARAM_STR); 
		$stmt1->bindParam(':date_add', date('Y-m-d H:i:s'), PDO::PARAM_STR);                                  
		$stmt1->execute();
	}
}


if($action == 'product'){
	$offset = $_REQUEST['offset'];
	$limit = $_REQUEST['limit'];
	
	$query2 = $db1->prepare('SELECT * FROM ps_product AS p 
		JOIN ps_product_lang AS pl ON pl.id_product = p.id_product
		WHERE pl.id_shop = 1 AND pl.id_lang = 1 AND pl.name !=""
		LIMIT '.$offset.','.$limit);
	$query2->execute();
	$products = $query2->fetchAll(PDO::FETCH_ASSOC);
	echo'<pre>',print_r($products),'</pre>';

	foreach ($products as $p) {
		$image = $p['link_rewrite'].'.jpg';
		$sqlInsert2 = "INSERT INTO product(id_category, name, price, quantity, image, description, date_add) VALUES (:id_category, :name, :price, :quantity, :image, :description, :date_add)";
		$stmt2 = $db2->prepare($sqlInsert2);       
		$stmt2->bindParam(':id_category', $p['id_category_default'], PDO::PARAM_STR);       
		$stmt2->bindParam(':name', $p['name'], PDO::PARAM_STR); 
		$stmt2->bindParam(':price', $p['price'], PDO::PARAM_STR); 
		$stmt2->bindParam(':quantity', $p['quantity'], PDO::PARAM_STR); 
		$stmt2->bindParam(':image', $image, PDO::PARAM_STR); 
		$stmt2->bindParam(':description', $p['description'], PDO::PARAM_STR); 
		$stmt2->bindParam(':date_add', date('Y-m-d H:i:s'), PDO::PARAM_STR);                                  
		$stmt2->execute();
	}
}
?>