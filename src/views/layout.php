<!DOCTYPE html>
<html>

<head>
    <title><?= htmlspecialchars($title ?? 'App') ?></title>
</head>

<body>
    <?php if (!empty($flash)): ?>
        <div style="color:green"><?= htmlspecialchars($flash) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div style="color:red"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?= $content ?>
</body>

</html>