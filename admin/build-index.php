<?php
include('../lib/Settings.php');

$indexing = isset($_GET['indexing']) ? $_GET['indexing'] : '';
switch ($indexing) {
    case 'category':
        $queryCat = "SELECT * FROM category";
        $categories = getResult($queryCat);
        foreach ($categories as $cat) {
            $params = [
                'id_parent' => $cat['id_parent'],
                'name' => $cat['name'],
            ];
            $jsonDoc = json_encode($params);
            $queryString = $cat['id'];
            $result = esCurlCall('ecommerce', 'category', $queryString, 'PUT', $jsonDoc);
            $result = json_decode($result);
        }
        echo'<pre>', print_r($result), '</pre>';
        if ($result->_shards->successful == 1) {
            echo "Category indexing successful";
        }

        break;
    case 'product':
        $queryProduct = "SELECT p.*, c.name AS category_name  FROM product AS p
        INNER JOIN category AS c ON c.id = p.id_category ";
        $product = getResult($queryProduct);
        #echo'<pre>', print_r($product), '</pre>';die;
        foreach ($product as $prod) {
            $params = [
                'id_category' => $prod['id_category'],
                'category_name' => $prod['category_name'],
                'name' => htmlentities($prod['name']),
                'price' => $prod['price'],
                'quantity' => $prod['quantity'],
                'description' => htmlentities($prod['description']),
                'image' => 'uploads/product/'.$prod['image']
            ];
            $jsonDoc = json_encode($params);
            $queryString = $prod['id'];
            $result = esCurlCall('ecommerce', 'product', $queryString, 'PUT', $jsonDoc);
            $result = json_decode($result);
            echo'<pre>', print_r($result), '</pre>';
        }
        //echo'<pre>', print_r($result), '</pre>';
        if ($result->_shards->successful == 1) {
            echo "product indexing successful";
        }
        break;
}
?>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
        <td width="15%">
            <?php include('left-menu.php'); ?>
        </td>
        <td width="85%">
            <ul >
                <li><a href="?indexing=category">Build Category Index</a></li>
                <li><a href="?indexing=product">Build product Index</a></li>
            </ul>
        </td>
    </tr>
</table>