<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather app</title>
</head>
<body>

<?php
try {
    $bdd = new PDO('mysql:host=localhost;dbname=weatherapp;charset=utf8', 'root', '');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['deleteButton'])) {
            $villeToDelete = $_POST['deleteButton'];
            $deleteQuery = $bdd->prepare("DELETE FROM `météo` WHERE ville = :ville");
            $deleteQuery->bindParam(':ville', $villeToDelete);
            $deleteQuery->execute();
        } else {
            $ville = $_POST['ville'];
            $haut = $_POST['haut'];
            $bas = $_POST['bas'];

            $insertQuery = $bdd->prepare("INSERT INTO `météo` (ville, haut, bas) VALUES (:ville, :haut, :bas)");
            $insertQuery->bindParam(':ville', $ville);
            $insertQuery->bindParam(':haut', $haut);
            $insertQuery->bindParam(':bas', $bas);
            $insertQuery->execute();
        }
    }

    $selectQuery = $bdd->query("SELECT * FROM `météo`");
    $weatherData = $selectQuery->fetchAll(PDO::FETCH_ASSOC);

} 
catch (PDOException $e) {
    error_log('Erreur PDO : ' . $e->getMessage());
    echo 'Une erreur est survenue. Veuillez réessayer plus tard.';
}
?>

<form method="post">
    <input type="text" name="ville" placeholder="Ville" required>
    <input type="text" name="haut" placeholder="Température maximun" required>
    <input type="text" name="bas" placeholder="Température minimun" required>
    <input type="submit" value="Add">
</form>

<section class="container">
    <table border="1">
        <tr>
            <th>Ville</th>
            <th>Haut</th>
            <th>Bas</th>
            <th>Del</th>
        </tr>
        <?php foreach ($weatherData as $row): ?>
            <tr>
                <td><?= $row['ville'] ?></td>
                <td><?= $row['haut'] ?></td>
                <td><?= $row['bas'] ?></td>
                <td>
                    <form method="post">
                        <input type="checkbox" name="delete[]" value="<?= $row['ville'] ?>">
                        <button type="submit" name="deleteButton" value="<?= $row['ville'] ?>">X</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>

</body>
</html>

