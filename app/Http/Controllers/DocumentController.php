<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Division;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Response;

class DocumentController extends Controller
{
    public function index(Request $request)
{
    $perPage = $request->input('perPage', 10); // default 10

    $query = Document::query();

    // ===============================
    // Role-based filtering
    // ===============================
    if (!auth()->user()->hasAnyRole(['admin', 'oed', 'records'])) {
        // For 'user' role, only show documents assigned to them
        $query->whereHas('users', function ($q) {
            $q->where('users.id', auth()->id());
        });
    }

    // ===============================
    // Filters
    // ===============================
    if ($request->filled('status') && $request->status !== 'all') {
        if ($request->status === 'no-status') {
            $query->whereNull('oed_status');
        } else {
            $query->where('oed_status', $request->status);
        }
    }

    if ($request->filled('document_type')) {
        $query->where('document_type', $request->document_type);
    }

    if ($request->filled('oed_received')) {
        if ($request->oed_received === 'Received') {
            $query->where('oed_received', 'Received');
        } elseif ($request->oed_received === 'Not yet received') {
            $query->whereNull('oed_received')
                  ->orWhere('oed_received', '!=', 'Received');
        }
    }

    if ($request->filled('oed_status')) {
        if ($request->oed_status === 'null') {
            $query->whereNull('oed_status');
        } else {
            $query->where('oed_status', $request->oed_status);
        }
    }

    if ($request->filled('records_received')) {
        if ($request->records_received === 'Received') {
            $query->where('records_received', 'Received');
        } elseif ($request->records_received === 'Not yet received') {
            $query->where(function ($q) {
                $q->whereNull('records_received')
                  ->orWhere('records_received', '!=', 'Received');
            });
        }
    }

    if ($request->filled('completed')) {
        if ($request->completed === 'Completed') {
            $query->whereNotNull('completed_at');
        } elseif ($request->completed === 'Not yet completed') {
            $query->whereNull('completed_at');
        }
    }

    // ===============================
    // Pagination
    // ===============================
    $documents = $query->orderBy('created_at', 'desc')
                       ->paginate($perPage)
                       ->appends($request->only([
                           'perPage', 'status', 'document_type', 'oed_received',
                           'oed_status', 'records_received', 'completed'
                       ]));

    // ===============================
    // Include divisions with users for modal
    // ===============================
    $divisions = Division::with('users')->get();

    // ===============================
    // Counts for cards
    // ===============================
    $countQuery = Document::query();

    // Apply the same user role filter
    if (!auth()->user()->hasAnyRole(['admin', 'oed', 'records'])) {
        $countQuery->whereHas('users', function ($q) {
            $q->where('users.id', auth()->id());
        });
    }

    $allCount = $countQuery->count();
    $inProgressCount  = (clone $countQuery)->where('oed_status', 'In Progress')->count();
    $underReviewCount = (clone $countQuery)->where('oed_status', 'Under Review')->count();
    $forReleaseCount  = (clone $countQuery)->where('oed_status', 'For Release')->count();
    $returnedCount    = (clone $countQuery)->where('oed_status', 'Returned')->count();
    $noStatusCount    = (clone $countQuery)->whereNull('oed_status')->count();
    $completedCount   = (clone $countQuery)->whereNotNull('completed_at')->count();

    return view('documents.index', compact(
        'documents',
        'perPage',
        'allCount',
        'inProgressCount',
        'underReviewCount',
        'forReleaseCount',
        'returnedCount',
        'noStatusCount',
        'completedCount',
        'divisions'
    ));
}

    // Show the form for creating a new document
    public function create()
    {
        return view('documents.create');
    }

    // Store a newly created document in storage
    public function store(Request $request)
    {
        $request->validate([
            'date_received' => 'required|date',
            'document_no' => 'required|string|max:255|unique:documents,document_no',
            'document_type' => 'required|string|max:255',
            'particulars' => 'required|string|max:255',
            'oed_received' => 'nullable|string|max:255',
            'oed_date_received' => 'nullable|date',
            'oed_status' => 'nullable|string|max:255',
            'oed_remarks' => 'nullable|string|max:255',
            'records_received' => 'nullable|string|max:255',
            'records_date_received' => 'nullable|date',
            'records_remarks' => 'nullable|string|max:255',
            'users' => 'required|array|min:1',    // ✅ handle users from checkboxes
            'users.*' => 'exists:users,id',
        ]);

        // ✅ Create the document (exclude users field)
        $document = Document::create($request->except('users'));

        // ✅ Attach users to pivot if selected
        if ($request->filled('users')) {
            $document->users()->sync($request->users); // sync avoids duplicate rows

            // Optional: log sending to each user
            foreach ($request->users as $userId) {
                DocumentLog::create([
                    'document_id' => $document->id,
                    'changed_by' => Auth::user()->name,
                    'type' => 'send',
                    'status' => "Sent to user_id: $userId",
                ]);
            }
        }

        return Auth::user()->hasRole('admin')
            ? redirect()->route('admin.documents.index')->with('success', 'Document created successfully.')
            : redirect()->route('documents.index')->with('success', 'Document created successfully.');
    }

    // Display the specified document
    public function show(Document $document)
    {
        return view('documents.show', compact('document'));
    }

    // Show the form for editing the specified document
    public function edit(Document $document)
    {
        return view('documents.edit', compact('document'));
    }

    // Update the specified document in storage
    public function update(Request $request, Document $document)
    {
        $request->validate([
            'date_received' => 'required|date',
            'document_no' => 'required|string|max:255|unique:documents,document_no,' . $document->id,
            'document_type' => 'required|string|max:255',
            'particulars' => 'required|string|max:255',
            'oed_received' => 'nullable|string|max:255',
            'oed_date_received' => 'nullable|date',
            'oed_status' => 'nullable|string|max:255',
            'oed_remarks' => 'nullable|string|max:255',
            'records_received' => 'nullable|string|max:255',
            'records_date_received' => 'nullable|date',
            'records_remarks' => 'nullable|string|max:255',
            'users' => 'required|array|min:1',
            'users.*' => 'exists:users,id',
        ]);

        $document->update($request->except('users'));

        if ($request->filled('users')) {
            $document->users()->sync($request->users); // sync pivot table
        }

        return Auth::user()->hasRole('admin')
            ? redirect()->route('admin.documents.index')->with('success', 'Document updated successfully.')
            : redirect()->route('documents.index')->with('success', 'Document updated successfully.');
    }

    // Remove the specified document from storage
    public function destroy(Document $document)
    {
        $document->delete();

        return Auth::user()->hasRole('admin')
            ? redirect()->route('admin.documents.index')->with('success', 'Document deleted successfully.')
            : redirect()->route('documents.index')->with('success', 'Document deleted successfully.');
    }

    public function markAsReceived(Document $document)
    {
        $document->update([
            'oed_date_received' => now(),
            'oed_received' => 'Received',
            'oed_status' => 'Under Review',
            'under_review_at' => $document->under_review_at ?? now(),
        ]);

        // Log the status change
        DocumentLog::create([
            'document_id' => $document->id,
            'changed_by' => Auth::user()->name,
            'type' => 'status',
            'status' => 'Under Review',
        ]);

        return redirect()->back()->with('success', 'Document marked as received and set to Under Review.');
    }

    public function updateOedStatus(Request $request, Document $document)
    {
        $request->validate([
            'oed_status' => 'required|in:Under Review,In Progress,For Release',
        ]);

        $updateData = ['oed_status' => $request->oed_status];

        // Preserve individual timestamps for logs
        if ($request->oed_status === 'Under Review' && !$document->under_review_at) {
            $updateData['under_review_at'] = now();
        } elseif ($request->oed_status === 'In Progress' && !$document->in_progress_at) {
            $updateData['in_progress_at'] = now();
        } elseif ($request->oed_status === 'For Release' && !$document->for_release_at) {
            $updateData['for_release_at'] = now();
        }

        $document->update($updateData);

        // Record log for this status change
        DocumentLog::create([
            'document_id' => $document->id,
            'changed_by' => Auth::user()->name,
            'type' => 'status',
            'status' => $request->oed_status,
        ]);

        return redirect()->back()->with('status', 'OED status updated.');
    }

    public function updateOedRemarks(Request $request, Document $document)
    {
        $request->validate([
            'oed_remarks' => 'nullable|string|max:255',
        ]);

        $document->update([
            'oed_remarks' => $request->oed_remarks,
        ]);

        return response()->json(['success' => true]);
    }

    public function markReceivedByRecords(Document $document)
    {
        $document->update([
            'records_received' => 'Received',
            'records_date_received' => now(),
        ]);

        // Record log for Records Section
        DocumentLog::create([
            'document_id' => $document->id,
            'changed_by' => Auth::user()->name,
            'type' => 'records',
            'status' => 'Received',
        ]);

        return redirect()->back()->with('success', 'Marked as received by Records.');
    }

    public function returnToOed(Document $document)
    {
        $document->update([
            'oed_status' => 'Returned',
            'forwarded_to_records' => null,
        ]);

        // ✅ Create a log entry for Returned
        DocumentLog::create([
            'document_id' => $document->id,
            'changed_by' => Auth::user()->name,
            'type' => 'status',
            'status' => 'Returned',
        ]);

        return redirect()->back()->with('success', 'Document returned to OED.');
    }

    public function updateRecordsRemarks(Request $request, Document $document)
    {
        $request->validate([
            'records_remarks' => 'nullable|string|max:255',
        ]);

        $document->update([
            'records_remarks' => $request->records_remarks,
        ]);

        return response()->json(['success' => true]);
    }

    public function markCompleted(Document $document)
    {
        $document->update([
            'completed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Document marked as completed.');
    }

    public function markForwardedToOED($id)
    {
        $document = Document::findOrFail($id);
        $document->forwarded_to_oed = now();
        $document->save();

        return back()->with('success', 'Document forwarded to OED successfully.');
    }

    public function markForwardedToRecords($id)
    {
        $document = Document::findOrFail($id);
        $document->forwarded_to_records = now();
        $document->save();

        return back()->with('success', 'Document forwarded to Records successfully.');
    }

    public function export(Request $request)
    {
        $query = Document::query();

        // Apply filters (same as index)
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'no-status') {
                $query->whereNull('oed_status');
            } else {
                $query->where('oed_status', $request->status);
            }
        }

        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        if ($request->filled('oed_received')) {
            if ($request->oed_received === 'Received') {
                $query->where('oed_received', 'Received');
            } elseif ($request->oed_received === 'Not yet received') {
                $query->whereNull('oed_received')
                    ->orWhere('oed_received', '!=', 'Received');
            }
        }

        if ($request->filled('records_received')) {
            if ($request->records_received === 'Received') {
                $query->where('records_received', 'Received');
            } elseif ($request->records_received === 'Not yet received') {
                $query->whereNull('records_received')
                    ->orWhere('records_received', '!=', 'Received');
            }
        }

        // Admin & Records can see all, otherwise filter by user
        if (!auth()->user()->hasAnyRole(['admin', 'records'])) {
            $query->whereHas('users', function ($q) {
                $q->where('users.id', auth()->id());
            });
        }

        $documents = $query->get([
            'date_received',
            'document_no',
            'document_type',
            'particulars',
            'oed_received',
            'oed_date_received',
            'oed_status',
            'forwarded_to_records',
            'records_received',
            'records_date_received',
            'records_remarks',
        ]);

        // CSV headers
        $csvHeader = [
            'Date Received',
            'Document No',
            'Document Type',
            'Particulars',
            'OED Received',
            'OED Date Received',
            'OED Status',
            'Forwarded to Records',
            'Records Received',
            'Records Date Received',
            'Records Remarks',
        ];

        $filename = 'documents_export_' . now()->format('Ymd_His') . '.csv';

        $callback = function () use ($documents, $csvHeader) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $csvHeader);

            foreach ($documents as $doc) {
                fputcsv($file, [
                    $doc->date_received?->format('m/d/Y H:i') ?? '',
                    $doc->document_no,
                    $doc->document_type,
                    $doc->particulars,
                    $doc->oed_received,
                    $doc->oed_date_received?->format('m/d/Y H:i') ?? '',
                    $doc->oed_status,
                    $doc->forwarded_to_records?->format('m/d/Y H:i') ?? '',
                    $doc->records_received,
                    $doc->records_date_received?->format('m/d/Y H:i') ?? '',
                    $doc->records_remarks,
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
        ]);
    }
}
