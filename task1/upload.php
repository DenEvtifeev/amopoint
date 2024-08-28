<?php
$targetDir = "files/";
$targetFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Проверка на тип файла
if ($fileType != "txt") {
    $uploadOk = 0;
}

// Проверка на ошибки загрузки
if ($uploadOk == 0) {
    header('Location: index.php?success=false');
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
        header('Location: index.php?success=true&filename=' . basename($_FILES["fileToUpload"]["name"]));
    } else {
        header('Location: index.php?success=false');
    }
}
exit;
