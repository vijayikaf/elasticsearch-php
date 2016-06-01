<?php
include('lib/Settings.php');

$q = isset($_REQUEST['q']) ? $_REQUEST['q'] : '';

if (!empty($q)) {
    $queryString = '_search';
    $params = [
        "query" => [
            "bool" => [
                "must" => [
                    "match" => ["name" => $q]
                ]
            ]   
        ]
    ];
   
    $jsonDoc = json_encode($params);
    
    $result = esCurlCall('ecommerce', 'category,product', $queryString, 'GET', $jsonDoc);
    $result = json_decode($result);
    #echo'<pre>',print_r($result),'</pre>';
    if ($result->hits->total > 0) {
        $results = $result->hits->hits;
        #echo'<pre>', print_r($results), '</pre>';
        ?>
        <ul id="country-list">
            <?php
            foreach ($results as $r) {
            ?>
                <li onClick="selectSuggesstion('<?php echo($r->_source->name) ?>');"><?php echo($r->_source->name) ?></li>
            <?php } ?>
        </ul>
        <?php
    }
}
/*
if (!empty($q)) {
    $sql = "SELECT `name` FROM `category` WHERE name LIKE '" . $q . "%' ORDER BY name ASC";
    $result = mysql_query($sql);
    ?>
    <ul id="country-list">
        <?php
        while($cat = mysql_fetch_assoc($result)) {
            ?>
            <li onClick="selectSuggesstion('<?php echo $cat["name"]; ?>');"><?php echo $cat["name"]; ?></li>
        <?php } ?>
    </ul>
    <?php
}
*/
?>