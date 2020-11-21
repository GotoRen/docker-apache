<?php
require_once("config/db_connect.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AMP test</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h3>Test Page.</h3>
<?php
    try {
        //$dbh = new PDO('mysql:host=localhost;dbname=test', 'root', 'root');
        $DBConnecter = new DBConnecter();
        $sql = "SELECT * FROM test";
        $stmt = $DBConnecter->pdo->prepare($sql);
        $stmt->execute();

        // 結果の取得
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($result as $row) {
            echo $row['msg'] . '<br>';
        }
    } catch (PDOException $Exception) {
        die('エラー :' . $Exception->getMessage());
    }
?>
</body>
</html>