<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enquiry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type', 'status', 'full_name', 'email', 'phone', 'customer_id',
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

    public const STATUSES = [
        'new', 'contacted', 'qualified', 'installation_scheduled',
        'installed', 'converted', 'rejected', 'closed',
    ];

    public function scopeOfType($q, $type)
    {
        return $type ? $q->where('type', $type) : $q;
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst(str_replace('_', ' ', (string) $this->type));
    }
}
