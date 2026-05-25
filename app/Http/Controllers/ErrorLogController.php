<?php

namespace App\Http\Controllers;

use App\Models\ErrorLog;
use App\Services\ErrorLoggingService;
use Illuminate\Http\Request;

class ErrorLogController extends Controller
{
    protected $errorLogger;

    public function __construct(ErrorLoggingService $errorLogger)
    {
        $this->errorLogger = $errorLogger;
    }

    /**
     * Display error logs dashboard.
     */
    public function index(Request $request)
    {
        $query = ErrorLog::with(['user', 'resolver']);

        // Filter by severity
        if ($request->has('severity') && $request->severity !== '') {
            $query->where('severity', $request->severity);
        }

        // Filter by resolved status
        if ($request->has('resolved') && $request->resolved !== '') {
            $query->where('resolved', $request->resolved === 'resolved');
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        // Search by message
        if ($request->has('search') && $request->search) {
            $query->where('message', 'like', '%' . $request->search . '%');
        }

        $errorLogs = $query->orderBy('created_at', 'desc')->paginate(50)->withQueryString();

        // Get statistics
        $stats = $this->errorLogger->getErrorStats(30);

        return view('admin.errors.index', compact('errorLogs', 'stats'));
    }

    /**
     * Show error log details.
     */
    public function show($id)
    {
        $errorLog = ErrorLog::with(['user', 'resolver'])->findOrFail($id);

        return view('admin.errors.show', compact('errorLog'));
    }

    /**
     * Mark error as resolved.
     */
    public function resolve(Request $request, $id)
    {
        $request->validate([
            'resolution_notes' => 'nullable|string|max:1000',
        ]);

        $errorLog = ErrorLog::findOrFail($id);
        $errorLog->markAsResolved($request->resolution_notes);

        return redirect()->back()->with('success', 'Error marked as resolved.');
    }

    /**
     * Delete error log.
     */
    public function destroy($id)
    {
        $errorLog = ErrorLog::findOrFail($id);
        $errorLog->delete();

        return redirect()->route('admin.errors.index')->with('success', 'Error log deleted.');
    }

    /**
     * Clear old error logs.
     */
    public function clearOld(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365',
        ]);

        $deleted = ErrorLog::where('created_at', '<', now()->subDays($request->days))
            ->where('resolved', true)
            ->delete();

        return redirect()->back()->with('success', "Deleted {$deleted} old error logs.");
    }
}
