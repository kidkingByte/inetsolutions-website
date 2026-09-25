<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enquiry extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'type', 'status', 'assigned_to', 'full_name', 'email', 'phone', 'customer_id',
        'region', 'district', 'ward', 'street', 'address', 'service_required',
        'preferred_package', 'preferred_installation_date', 'subject',
        'problem_type', 'message', 'attachment', 'admin_notes', 'source', 'ip_address',
    ];

    protected $casts = ['preferred_installation_date' => 'date'];

    public const TYPES = [
        'connection_request' => 'Connection Request',
        'coverage_request' => 'Coverage Request',
        'business_inquiry' => 'Business Inquiry',
        'enterprise_inquiry' => 'Enterprise Inquiry',
        'support_request' => 'Support Request',
        'contact_form' => 'Contact Form',
        'callback_request' => 'Callback Request',
        'coverage_notify' => 'Coverage Notify Me',
    ];

    /** Handled by the support team (support.* permissions); every other type is a sales lead (leads.*). */
    public const SUPPORT_TYPES = ['support_request'];

    public const STATUSES = [
        'new', 'contacted', 'qualified', 'installation_scheduled',
        'installed', 'converted', 'rejected', 'closed',
    ];

    /** Statuses that mean no further follow-up is needed. */
    public const CLOSED_STATUSES = ['installed', 'converted', 'rejected', 'closed'];

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function scopeOfType($q, $type)
    {
        return $type ? $q->where('type', $type) : $q;
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->whereNotIn('status', self::CLOSED_STATUSES);
    }

    /** Limit to the enquiry types the user may see: sales leads, support tickets, or both. */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        return $query->whereIn('type', self::typesVisibleTo($user));
    }

    /** @return list<string> */
    public static function typesVisibleTo(User $user): array
    {
        return array_values(array_filter(
            array_keys(self::TYPES),
            fn (string $type) => $user->can(self::area($type).'.view'),
        ));
    }

    /** Permission area for a type: "support" or "leads". */
    public static function area(string $type): string
    {
        return in_array($type, self::SUPPORT_TYPES, true) ? 'support' : 'leads';
    }

    public function isSupport(): bool
    {
        return self::area($this->type) === 'support';
    }

    /** The permission needed for an action on this enquiry, e.g. "leads.manage" or "support.view". */
    public function permission(string $action): string
    {
        return self::area($this->type).'.'.$action;
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst(str_replace('_', ' ', (string) $this->type));
    }

    public function getStatusLabelAttribute(): string
    {
        return ucfirst(str_replace('_', ' ', (string) $this->status));
    }

    /** Phone in wa.me format: digits only, local "07…" numbers get Tanzania's 255 prefix. */
    public function getWhatsappNumberAttribute(): ?string
    {
        $digits = preg_replace('/\D/', '', (string) $this->phone);

        return $digits === '' ? null : (str_starts_with($digits, '0') ? '255'.substr($digits, 1) : $digits);
    }

    public function auditLabel(): string
    {
        return "{$this->type_label} #{$this->id}".($this->full_name ? " — {$this->full_name}" : '');
    }
}
