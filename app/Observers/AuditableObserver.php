<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditableObserver
{
    public function created(Model $model)
    {
        if (! $this->shouldAudit($model)) {
            return;
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => AuditLog::CREATED,
            'auditable_type' => $model->getMorphClass(),
            'auditable_id' => $model->getKey(),
            'old_values' => null,
            'new_values' => $this->sanitize($model->getAttributes()),
        ]);
    }

    public function updated(Model $model)
    {
        if (! $this->shouldAudit($model)) {
            return;
        }

        $changes = $model->getChanges();
        unset($changes['updated_at']);

        if (empty($changes)) {
            return;
        }

        $old = [];
        $new = [];

        foreach (array_keys($changes) as $field) {
            $old[$field] = $model->getOriginal($field);
            $new[$field] = $model->getAttribute($field);
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => AuditLog::UPDATED,
            'auditable_type' => $model->getMorphClass(),
            'auditable_id' => $model->getKey(),
            'old_values' => $this->sanitize($old),
            'new_values' => $this->sanitize($new),
        ]);
    }

    public function deleted(Model $model)
    {
        if (! $this->shouldAudit($model)) {
            return;
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => AuditLog::DELETED,
            'auditable_type' => $model->getMorphClass(),
            'auditable_id' => $model->getKey(),
            'old_values' => $this->sanitize($model->getOriginal()),
            'new_values' => null,
        ]);
    }

    protected function shouldAudit(Model $model): bool
    {
        if (property_exists($model, 'auditEnabled') && $model->auditEnabled === false) {
            return false;
        }

        return true;
    }

    protected function sanitize(array $values): array
    {
        $hiddenFields = [
            'password',
            'remember_token',
            'deleted_at',
        ];

        foreach ($hiddenFields as $field) {
            unset($values[$field]);
        }

        return $values;
    }
}
