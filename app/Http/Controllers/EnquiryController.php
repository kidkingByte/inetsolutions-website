<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Package;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function getConnected(Request $request)
    {
        return view('site.get-connected', [
            'packages' => Package::published()->ordered()->get(),
            'service' => $request->query('service'),
            'package' => $request->query('package'),
        ]);
    }

    public function storeConnection(Request $request)
    {
        $data = $request->validate($this->connectionRules(), $this->messages());

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('enquiries', 'public');
        }

        Enquiry::create($data + [
            'type' => $this->normalizeInquiryType($request->input('type')),
            'source' => $request->path(),
            'ip_address' => $request->ip(),
        ]);

        return back()->with('status', 'Thank you! Your request has been received. Our team will contact you shortly.');
    }

    public function contact()
    {
        return view('site.contact');
    }

    public function storeContact(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:120'],
            'subject' => ['required', 'string', 'max:160'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        Enquiry::create($data + [
            'type' => 'contact_form',
            'source' => $request->path(),
            'ip_address' => $request->ip(),
        ]);

        return back()->with('status', 'Thank you for contacting INET SOLUTIONS LTD. Our team will get back to you shortly.');
    }

    public function support()
    {
        return view('site.report-problem');
    }

    public function storeSupport(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'customer_id' => ['nullable', 'string', 'max:60'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'problem_type' => ['required', 'string', 'max:60'],
            'message' => ['required', 'string', 'max:2000'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('enquiries', 'public');
        }

        Enquiry::create($data + [
            'type' => 'support_request',
            'source' => $request->path(),
            'ip_address' => $request->ip(),
        ]);

        return back()->with('status', 'Your support request has been submitted. Our team will assist you shortly.');
    }

    protected function normalizeInquiryType(?string $type): string
    {
        return match ($type) {
            'business_inquiry', 'enterprise_inquiry', 'callback_request' => $type,
            default => 'connection_request',
        };
    }

    protected function connectionRules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:120'],
            'region' => ['required', 'string', 'max:120'],
            'district' => ['nullable', 'string', 'max:120'],
            'ward' => ['nullable', 'string', 'max:120'],
            'street' => ['nullable', 'string', 'max:120'],
            'address' => ['nullable', 'string', 'max:255'],
            'service_required' => ['nullable', 'string', 'max:60'],
            'preferred_package' => ['nullable', 'string', 'max:120'],
            'preferred_installation_date' => ['nullable', 'date'],
            'message' => ['nullable', 'string', 'max:2000'],
        ];
    }

    protected function messages(): array
    {
        return [
            'full_name.required' => 'Please enter your full name.',
            'phone.required' => 'Please enter a phone number we can reach you on.',
            'region.required' => 'Please enter your region.',
        ];
    }
}
