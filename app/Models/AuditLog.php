<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLog extends Model
{
    public const UPDATED_AT = null;

    public const EVENTS = [
        'created' => 'Created',
        'updated' => 'Updated',
        'deleted' => 'Deleted',
        'login' => 'Signed in',
        'logout' => 'Signed out',
        'login_failed' => 'Failed sign-in',
        'exported' => 'Exported',
    ];

    protected $fillable = ['user_id', 'event', 'auditable_type', 'auditable_id', 'description', 'changes', 'ip_address'];

    protected $casts = ['changes' => 'array', 'created_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    /** Record an action by the signed-in staff member (or by $user when given, e.g. on login). */
    public static function record(string $event, ?Model $subject = null, ?string $description = null, ?array $changes = null, ?User $user = null): self
    {
        return static::create([
            'user_id' => ($user ?? Auth::user())?->getKey(),
            'event' => $event,
            'auditable_type' => $subject?->getMorphClass(),
            'auditable_id' => $subject?->getKey(),
            'description' => $description,
            'changes' => $changes ?: null,
            'ip_address' => Request::ip(),
        ]);
    }

    public function getEventLabelAttribute(): string
    {
        return self::EVENTS[$this->event] ?? ucfirst($this->event);
    }

    /** Short model name for display, e.g. "Enquiry". */
    public function getSubjectTypeLabelAttribute(): ?string
    {
        return $this->auditable_type ? class_basename($this->auditable_type) : null;
    }
}
