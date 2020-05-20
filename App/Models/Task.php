<?php

namespace App\Models;

use ActiveRecord\Model;


/**
 * Class User
 * @package App\Models
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $text
 * @property string $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $updated_by
 */
class Task extends Model
{
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETE = 'complete';

    public static function newTodo(string $username, string $email, string $text): self
    {
        $task = new static();
        $task->username = $username;
        $task->email = $email;
        $task->text = $text;
        $task->status = self::STATUS_ACTIVE;
        return $task;
    }

    public function edit(string $username, string $email, string $text, ?string $updated_by = null)
    {
        $this->username = $username;
        $this->email = $email;
        $this->text = $text;
        $this->updated_by = $updated_by;
    }

    public function set_timestamps()
    {
        parent::set_timestamps();
        if ($this->updated_at) {
            $this->updated_at = time();
        }
        if ($this->created_at) {
            $this->created_at = time();
        }
    }

    public function statuses()
    {
        return [
            self::STATUS_ACTIVE => 'Активный',
            self::STATUS_COMPLETE => $this->updated_by ? 'Выполнено и отредактировано администратором' : 'Выполнено',
        ];
    }

    public function statusName()
    {
        return $this->statuses()[$this->status];
    }

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isComplete()
    {
        return $this->status === self::STATUS_COMPLETE;
    }

    public function statusComplete()
    {
        $this->status = self::STATUS_COMPLETE;
        $this->updated_at = time();
    }
}
