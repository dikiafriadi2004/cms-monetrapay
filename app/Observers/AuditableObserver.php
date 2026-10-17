<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

/**
 * Generic auditable observer — logs create/update/delete events
 * for any model that registers it.
 */
class AuditableObserver
{
    public function created(Model $model): void
    {
        AuditLog::record(
            $this->actionPrefix($model).'.created',
            ['attributes' => $this->safeAttributes($model)],
            $model,
        );
    }

    public function updated(Model $model): void
    {
        $changes = array_keys($model->getChanges());
        // Skip if the only change is updated_at
        if ($changes === ['updated_at'] || empty($changes)) {
            return;
        }

        AuditLog::record(
            $this->actionPrefix($model).'.updated',
            ['changed' => $changes],
            $model,
        );
    }

    public function deleted(Model $model): void
    {
        AuditLog::record(
            $this->actionPrefix($model).'.deleted',
            ['attributes' => $this->safeAttributes($model)],
            $model,
        );
    }

    public function restored(Model $model): void
    {
        AuditLog::record(
            $this->actionPrefix($model).'.restored',
            [],
            $model,
        );
    }

    public function forceDeleted(Model $model): void
    {
        AuditLog::record(
            $this->actionPrefix($model).'.force_deleted',
            ['attributes' => $this->safeAttributes($model)],
            $model,
        );
    }

    /**
     * Extract a short snake_case prefix from the model class.
     */
    protected function actionPrefix(Model $model): string
    {
        return strtolower(class_basename($model));
    }

    /**
     * Filter out sensitive/large fields from being stored in the log.
     */
    protected function safeAttributes(Model $model): array
    {
        $attrs = $model->getAttributes();

        // Never log secrets
        unset($attrs['password'], $attrs['remember_token'], $attrs['api_token']);

        // Truncate long content fields
        foreach (['content', 'message', 'description'] as $key) {
            if (isset($attrs[$key]) && is_string($attrs[$key]) && strlen($attrs[$key]) > 200) {
                $attrs[$key] = substr($attrs[$key], 0, 200).'…';
            }
        }

        return $attrs;
    }
}
