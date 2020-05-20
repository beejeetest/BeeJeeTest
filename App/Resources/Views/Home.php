<?php
/**
 * @var array $params
 * @var App\Models\Task $task
 * @var \App\Helpers\Sorter $sorter
 */

if (empty($params['task']) && empty($params['data'])) {
    die('not params');
} else {
    $task = isset($params['task']) ? $params['task'] : '';
    $tasks = $params['data']['records'];
    $pagination = $params['data']['page_links'];
    $isUpdate = $task ? true : false;
}
?>
<div class="card mt-3">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h1 class="card-title">Задачи</h1>
            <a class="btn btn-link ml-auto" data-toggle="collapse" href="#add" role="button" aria-expanded="false"
               aria-controls="add">
                Добавить задачу
            </a>
        </div>
        <div class="collapse <?= $isUpdate ? 'show' : '' ?>" id="add">
            <form action="/home/<?= $isUpdate ? "update/{$task->id}" : 'create' ?>" method="post" class="mt-2">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="username">Имя пользователя</label>
                            <input autofocus id="username" type="text" name="username" class="form-control"
                                   value="<?= $isUpdate ? $task->username : '' ?>">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="email"> Эл. почта</label>
                            <input id="email" type="email" name="email" class="form-control"
                                   value="<?= $isUpdate ? $task->email : '' ?>">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="text">Текст задачи</label>
                            <textarea id="text" rows="4" name="text" class="form-control"><?= $isUpdate
                                    ? $task->text
                                    : '' ?></textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-success"><?= $isUpdate ? 'Отредактировать задачу и ' : '' ?>Сохранить</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
    <div class="card-body d-flex flex-column">
        <?php if ($tasks): ?>
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID <?= $sorter->getSorterLink('id') ?></th>
                        <th scope="col">Имя пользователя <?= $sorter->getSorterLink('username') ?></th>
                        <th scope="col">Эл. почта <?= $sorter->getSorterLink('email') ?></th>
                        <th scope="col">Текс задачи <?= $sorter->getSorterLink('text') ?></th>
                        <th scope="col">Состояние <?= $sorter->getSorterLink('status') ?></th>
                        <th scope="col">Дата добавления <?= $sorter->getSorterLink('create_at') ?></th>
                        <th scope="col">Дата обновления <?= $sorter->getSorterLink('updated_at') ?></th>
                        <?php if (isset($_SESSION['user'])): ?>
                            <th scope="col">Действие</th>
                        <?php endif; ?>

                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($tasks as $task): ?>
                        <?php /** @var $task \App\Models\Task */ ?>
                        <tr>
                            <th scope="row"><?= $task->id ?></th>
                            <td><?= $task->username ?></td>
                            <td><?= $task->email ?></td>
                            <td><?= $task->text ?></td>
                            <td><?= $task->statusName() ?></td>
                            <td><?= date('d/m/Y H:m', $task->created_at) ?></td>
                            <td><?= date('d/m/Y H:m', $task->updated_at) ?></td>
                            <?php if (isset($_SESSION['user'])): ?>
                                <td>
                                    <?php if ($task->isActive()): ?>
                                        <a class="btn-sm btn btn-info mr-1" href="/home/complete/<?= $task->id ?>">Выполнено</a>
                                    <?php endif; ?>
                                    <?php if (!$isUpdate): ?>
                                        <a class="btn-sm btn btn-warning ml-1" href="/home/index/<?= $task->id ?>">Редактировать</a>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <nav class="ml-auto">
                    <?= $pagination ?>
                </nav>
            </div>
        <?php else: ?>
            <h2 class="text-center text-secondary">Задач не найдено</h2>
        <?php endif; ?>
    </div>
</div>
