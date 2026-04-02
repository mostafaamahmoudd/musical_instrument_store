<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InquiryUpdateRequest;
use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();
        $inquiries = Inquiry::query()
            ->with([
                'instrument.spec.builder',
                'instrument.spec.instrumentType',
                'assignedAdmin',
                'user',
            ])
            ->ofStatus($status)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.inquiries.index', [
            'inquiries' => $inquiries,
            'selectedStatus' => $status,
            'statuses' => Inquiry::statuses(),
        ]);
    }

    public function show(Inquiry $inquiry)
    {
        $inquiry->load([
            'instrument.media',
            'instrument.spec.builder',
            'instrument.spec.instrumentType',
            'instrument.spec.instrumentFamily',
            'assignedAdmin',
            'user',
        ]);

        $admins = User::query()
            ->ofType(User::ADMIN_TYPE)
            ->orderBy('name')
            ->get();

        return view('admin.inquiries.show', [
            'inquiry' => $inquiry,
            'statuses' => Inquiry::statuses(),
            'admins' => $admins,
        ]);
    }

    public function update(InquiryUpdateRequest $request, Inquiry $inquiry)
    {
        $inquiry->update([
            'status' => $request->string('status')->toString(),
            'assigned_admin_id' => $request->input('assigned_admin_id') ?: null,
        ]);

        return redirect()
            ->route('admin.inquiries.show', compact('inquiry'))
            ->with('success', 'Inquiry updated successfully.');
    }
}
