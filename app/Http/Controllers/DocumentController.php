<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    // Display a listing of documents
    public function index()
    {
        $documents = Document::latest()->paginate(10);
        return view('documents.index', compact('documents'));
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
            'document_no' => 'required|string|max:255',
            'document_type' => 'required|string|max:255',
            'particulars' => 'required|string|max:255',
            'oed_received' => 'nullable|string|max:255',
            'oed_date_received' => 'nullable|date',
            'oed_status' => 'nullable|string|max:255',
            'oed_remarks' => 'nullable|string|max:255',
            'records_received' => 'nullable|string|max:255',
            'records_date_received' => 'nullable|date',
            'records_remarks' => 'nullable|string|max:255',
        ]);

        Document::create($request->all());

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
            'document_no' => 'required|string|max:255',
            'document_type' => 'required|string|max:255',
            'particulars' => 'required|string|max:255',
            'oed_received' => 'nullable|string|max:255',
            'oed_date_received' => 'nullable|date',
            'oed_status' => 'nullable|string|max:255',
            'oed_remarks' => 'nullable|string|max:255',
            'records_received' => 'nullable|string|max:255',
            'records_date_received' => 'nullable|date',
            'records_remarks' => 'nullable|string|max:255',
        ]);

        $document->update($request->all());

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
        ]);

        return redirect()->back()->with('success', 'Document marked as received.');
    }

    public function updateOedStatus(Request $request, Document $document)
    {
        $request->validate([
            'oed_status' => 'required|in:Under Review,In Progress,For Release',
        ]);

        $updateData = ['oed_status' => $request->oed_status];

        if ($request->oed_status === 'Under Review' && !$document->under_review_at) {
            $updateData['under_review_at'] = now();
        } elseif ($request->oed_status === 'In Progress' && !$document->in_progress_at) {
            $updateData['in_progress_at'] = now();
        } elseif ($request->oed_status === 'For Release' && !$document->for_release_at) {
            $updateData['for_release_at'] = now();
        }

        $document->update($updateData);

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

        return redirect()->back()->with('success', 'Marked as received by Records.');
    }

    public function returnToOed(Document $document)
    {
        $document->update([
            'oed_status' => 'Returned',
            'for_release_at' => null, // Optional: clear timestamp
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

}
