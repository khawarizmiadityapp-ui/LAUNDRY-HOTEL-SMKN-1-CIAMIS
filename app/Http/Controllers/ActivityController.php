<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    /**
     * Display activity log index page
     */
    public function index(Request $request)
    {
        $query = Activity::with('causer', 'subject')
            ->latest();

        // Filter by model type
        if ($request->filled('model')) {
            $query->where('subject_type', $request->model);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }

        // Filter by event (created, updated, deleted)
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by description
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $activities = $query->paginate(20);

        // Get unique models for filter
        $models = Activity::select('subject_type')
            ->distinct()
            ->pluck('subject_type')
            ->map(function ($model) {
                return [
                    'value' => $model,
                    'label' => class_basename($model)
                ];
            });

        // Get users who have activities
        $users = \App\Models\User::whereIn('id', Activity::select('causer_id')->distinct()->pluck('causer_id'))
            ->get(['id', 'name']);

        return view('admin.activity.index', compact('activities', 'models', 'users'));
    }

    /**
     * Show detailed activity log
     */
    public function show($id)
    {
        $activity = Activity::with('causer', 'subject')->findOrFail($id);

        return view('admin.activity.show', compact('activity'));
    }
}
