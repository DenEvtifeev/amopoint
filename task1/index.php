<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Загрузка файла</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h1>Загрузите .txt файл</h1>
<form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="fileToUpload" id="fileToUpload" accept=".txt">
    <button type="submit">Загрузить</button>
</form>

<div class="upload-result">
    <?php if (isset($_GET['success'])): ?>
        <?php if ($_GET['success'] == 'true'): ?>
            <div class="circle green"></div>
        <?php else: ?>
            <div class="circle red"></div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php if (isset($_GET['success']) && $_GET['success'] == 'true' && isset($_GET['filename'])): ?>
    <h2>Результат обработки файла:</h2>
        <p><?php
        $filename = 'files/' . basename($_GET['filename']);
        $delimiter = ','; // Заданный символ для разбивки строк

        if (file_exists($filename)) {
            $fileContent = file_get_contents($filename);
            $lines = explode($delimiter, $fileContent);
            foreach ($lines as $line) {
                $digitCount = preg_match_all('/\d/', $line);
                echo "Строка: $line = $digitCount цифр".'</br>';
            }
        }
        ?>
        </p>
<?php endif; ?>
</body>
</html>
