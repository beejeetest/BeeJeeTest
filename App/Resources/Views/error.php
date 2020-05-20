<?php foreach ($params['errors'] as $error): ?>
    <h1># <?= $error['code'] ?></h1>
    <p><?= $error['message'] ?></p>
<?php endforeach; ?>
