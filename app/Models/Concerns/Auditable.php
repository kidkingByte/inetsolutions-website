<?php

namespace App\Models\Concerns;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Writes an audit log entry whenever a signed-in staff member creates, updates or deletes the model.
 * Changes made by website visitors (e.g. a new enquiry) or seeders are not logged.
 */
trait Auditable
{
    /** Attributes never written to the audit log. */
    protected static array $auditIgnored = ['password', 'remember_token', 'created_at', 'updated_at', 'deleted_at', 'last_login_at', 'views'];

    public static function bootAuditable(): void
    {
        static::created(fn (Model $model) => $model->writeAudit('created', $model->auditValues($model->getAttributes(), null)));

        static::updated(function (Model $model) {
            $changes = $model->auditValues($model->getChanges(), $model->getOriginal());
            if ($changes) {
                $model->writeAudit('updated', $changes);
            }
        });

        static::deleted(fn (Model $model) => $model->writeAudit('deleted'));
    }

    /** Human-readable name for the audit log, e.g. "Home Plus" or "Enquiry #12". */
    public function auditLabel(): string
    {
        foreach (['name', 'title', 'full_name', 'question', 'service', 'label', 'key', 'region'] as $attribute) {
            if (filled($this->getAttribute($attribute))) {
                return (string) $this->getAttribute($attribute);
            }
        }

        return class_basename($this).' #'.$this->getKey();
    }

    protected function writeAudit(string $event, ?array $changes = null): void
    {
        if (! Auth::check()) {
            return;
        }

        AuditLog::record($event, $this, $this->auditLabel(), $changes);
    }

    protected function auditValues(array $new, ?array $old): array
    {
        $values = [];

        foreach ($new as $key => $value) {
            if (in_array($key, static::$auditIgnored, true) || in_array($key, $this->getHidden(), true)) {
                continue;
            }
            $values[$key] = $old === null ? ['new' => $value] : ['old' => $old[$key] ?? null, 'new' => $value];
        }

        return $values;
    }
}
