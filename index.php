<?php
include('lib/Settings.php');

$q = isset($_GET['q']) ? $_GET['q'] : '';

if (!empty($q)) {
    $queryString = '_search';
    $params = [
        "from" => 0,
        "size" => 20, //get 20 records
        "query" => [
            "multi_match" => [
                "query" => $q,
                "type" => "best_fields",
                "fields" => ["name", "category_name"],
                "operator" => "and"
            ]
        ]
    ];
   
    $jsonDoc = json_encode($params, JSON_PRETTY_PRINT);
    $result = esCurlCall('ecommerce', 'product', $queryString, 'GET', $jsonDoc);
    $result = json_decode($result);
    #echo'<pre>',print_r($result),'</pre>';
    if ($result->hits->total > 0) {
        $results = $result->hits->hits;
    }
    #echo'<pre>', print_r($results), '</pre>';
}


?>
</<!DOCTYPE html>
<html>
    <head>
        <title>Search | ES</title>
        <script src="js/jquery.min.js" type="text/javascript"></script>
        <style>
            body{width:50%;margin: 0 auto;}
            .frmSearch {/*border: 1px solid #F0F0F0;*/margin: 2px 0px;padding:40px;}
            #country-list{float:left;list-style:none;margin:0;padding:0;width:auto; min-width: 505px;}
            #country-list li{padding: 10px; background:#FAFAFA;border-bottom:#F0F0F0 1px solid;}
            #country-list li:hover{background:#F0F0F0; cursor: pointer;}
            #search-box{padding: 10px;border: #F0F0F0 1px solid; min-width: 500px;}
            input[type="submit"]{border: 1px solid #e3e3e3; padding: 8px 10px; cursor: pointer;}

            .products-list {
                width: 100%;
                overflow: hidden;
                margin-bottom: 10px;
            }
            .products-list a{
                float: left;
                margin-bottom: 10px;
                width: 100%;
            }
            .product-left {
                float: left;
                margin-right: 10px;
                width: 10%;
            }
            .product-right{
                float: left;
                width: 88%;
            }
        </style>
    </head>
    <body>
        <div class="frmSearch">
            <form method="get" action="index.php">
                <input type="text" id="search-box" name="q" value="<?php echo($q); ?>" autocomplete="off">
                <input type="submit" value="Seach">
                <div id="suggesstion-box"></div>
            </form>
            
            <?php if (isset($results)) { ?>
                <?php foreach ($results as $r) {
                    ?>
                    <div class="products-list">    
                        <div class="product-left"><img src="<?php echo($r->_source->image) ?>" width="60" height="60"></div>
                        <dic class="product-right">
                            <div class="name"><a href="#"><?php echo($r->_source->name) ?></a></div>
                            <div class="price">&#x20B9; <?php echo($r->_source->price) ?></div>
                        </dic>
                    </div>     
                <?php } ?>
            <?php } ?>
        </div>	
    </body>
    <script>
            $(document).ready(function () {
                $("#search-box").keyup(function () {
                    $.ajax({
                        type: "POST",
                        url: "ajax.php",
                        data: 'q=' + $(this).val(),
                        beforeSend: function () {
                            //$("#search-box").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
                        },
                        success: function (data) {
                            //console.log(data);
                            $("#suggesstion-box").show();
                            $("#suggesstion-box").html(data);
                        }
                    });
                });
            });

            function selectSuggesstion(val) {
                $("#search-box").val(val);
                $("#suggesstion-box").hide();
            }
        </script>
</html>