<?php

namespace App\Http\Controllers;

use App\Models\RequestEvent;
use App\Models\ServiceRequest;
use App\Services\Telegram;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RequestController extends Controller
{
    public function store(Request $request, Telegram $telegram)
    {
        // Honeypot against bots
        if (filled($request->input('website'))) {
            return back()->with('success', __('site.form.sent'));
        }

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'phone' => 'required|string|max:40',
            'email' => 'nullable|email|max:150',
            'department_id' => 'nullable|exists:departments,id',
            'client_type' => ['nullable', Rule::in(ServiceRequest::CLIENT_TYPES)],
            'message' => 'required|string|max:5000',
            'agree' => 'accepted',
        ]);
        unset($data['agree']);

        $sr = ServiceRequest::create($data + [
            'locale' => app()->getLocale(),
            'ip' => $request->ip(),
        ]);
        RequestEvent::create([
            'service_request_id' => $sr->id,
            'type' => 'created',
            'body' => null,
            'visible_to_client' => true,
        ]);

        $telegram->notifyRequest($sr->fresh('department'));

        return back()->with('success', __('site.form.sent_number', ['number' => $sr->fresh()->number]));
    }
}
