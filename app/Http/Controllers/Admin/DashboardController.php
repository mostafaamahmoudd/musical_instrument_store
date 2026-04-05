<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Inquiry;
use App\Models\Instrument;
use App\Models\PriceHistory;
use App\Models\Reservation;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $metrics = [
            'total_instruments' => Instrument::count(),
            'published_instruments' => Instrument::whereNotNull('published_at')->count(),
            'available_instruments' => Instrument::where('stock_status', Instrument::AVAILABLE)->count(),
            'pending_inquiries' => Inquiry::where('status', Inquiry::NEW)->count(),
            'pending_reservations' => Reservation::where('status', Reservation::PENDING)->count(),
        ];

        $latestAuditLogs = AuditLog::query()
            ->with('user')
            ->latest()
            ->take(8)
            ->get();

        $latestPriceChanges = PriceHistory::query()
            ->with(['instrument', 'changedBy'])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'metrics',
            'latestAuditLogs',
            'latestPriceChanges'
        ));
    }
}
