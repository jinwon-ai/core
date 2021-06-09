<html>
<head>
    <style>
        body {
            font-family: Consolas;
        }
    </style>
</head>
<body>

<?php
include "core/tree.php";

$conn = mysqli_connect(
  'localhost',
  'dshareai',
  'adotAI!@34',
  'dshareai');
$sql = "SELECT * FROM `SentenceTrees` WHERE `sentence_id`='11'";
$result = mysqli_query($conn, $sql);
$data = array();
while($row = mysqli_fetch_assoc($result))
    array_push($data, $row);
//echo "<pre>";
//print_r($data);
//echo "</pre>";
//exit();

echo "<h1>[PHP - Sentence Tree]</h1>" . "<hr>";

function callback($node) {
    echo "[" . str_replace(' ', '&nbsp;', str_pad($node['id'], 7, " ")) . "]";
    echo "[" . str_replace(' ', '&nbsp;', str_pad($node['tag'], 8, " ")) . "] ";
    echo $node['text'] . "<br>";
}

$tree = new Tree($data);

echo "DFS>>>" . "<br>";
$tree->setMethod('DFS');
$tree->start();
while ($tree->next(callback));
echo "<hr>";

echo "BFS>>>" . "<br>";
$tree->setMethod('BFS');
$tree->start();
while ($tree->next(callback));
echo "<hr>";

echo "RDFS>>>" . "<br>";
$tree->setMethod('RDFS');
$tree->start();
while ($tree->next(callback));
echo "<hr>";

?>

<h1>[Javascript - Sentence Tree]</h1><hr>
<div id="javascript_sentence_tree"></div>

<script src="core/tree.js"></script>
<script>
    var div_obj = document.getElementById('javascript_sentence_tree');
    function callback(node) {
        var str = '[' + Utils.pad(node['id'], 7, ' ') + ']';
        str = str + '[' + Utils.pad(node['tag'], 8, ' ') + '] ';
        str = str + node['text'];
        console.log(str);

        str = '[' + Utils.pad(node['id'], 7, '&nbsp;') + ']';
        str = str + '[' + Utils.pad(node['tag'], 8, '&nbsp;') + '] ';
        str = str + node['text'];
        div_obj.innerHTML = div_obj.innerHTML + str + '<br>';
    }

    var data = <?php echo json_encode($data); ?>;
    let tree = new Tree(data);

    console.log('DFS>>>');
    div_obj.innerHTML = div_obj.innerHTML + 'DFS>>>' + '<br>';
    tree.setMethod('DFS');
    tree.start();
    while (tree.next(callback));
    div_obj.innerHTML = div_obj.innerHTML + '<hr>';

    console.log('BFS>>>');
    div_obj.innerHTML = div_obj.innerHTML + 'BFS>>>' + '<br>';
    tree.setMethod('BFS');
    tree.start();
    while (tree.next(callback));
    div_obj.innerHTML = div_obj.innerHTML + '<hr>';

    console.log('RDFS>>>');
    div_obj.innerHTML = div_obj.innerHTML + 'RDFS>>>' + '<br>';
    tree.setMethod('RDFS');
    tree.start();
    while (tree.next(callback));
    div_obj.innerHTML = div_obj.innerHTML + '<hr>';

</script>

</body>
</html>