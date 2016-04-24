<?php
    $apiDir = dirname(dirname(dirname(__FILE__))).'/api';
    $file = $apiDir.'/notes.md';
    $data = file_get_contents($file);
    $classes = explode("\n*******************************************************************************\n", $data);
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style type="text/css">
        pre {
            font-family: "Fira Mono";
            font-size: 12px;
            line-height: 1.6em;
        }
        @media print {
            pre {
                page-break-after: always;
            }
        }
    </style>
</head>
<body>
<?php foreach ($classes as $class) : ?>
    <pre><?php echo $class; ?></pre>
<?php endforeach; ?>
</body>
</html>