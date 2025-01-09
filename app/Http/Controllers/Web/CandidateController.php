<?php

namespace App\Http\Controllers\Web;

use App\Models\Candidate;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CandidateStoreRequest;
use App\Http\Requests\CandidateUpdateRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware as ControllersMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class CandidateController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new ControllersMiddleware(PermissionMiddleware::using('candidate-view'), only: ['index', 'show']),
            new ControllersMiddleware(PermissionMiddleware::using('candidate-create'), only: ['create', 'store']),
            new ControllersMiddleware(PermissionMiddleware::using('candidate-update'), only: ['edit', 'update']),
            new ControllersMiddleware(PermissionMiddleware::using('candidate-delete'), only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $candidates = Candidate::all();

        return view('pages.app.candidate.index', compact('candidates'));
    }

    /*
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.app.candidate.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CandidateStoreRequest $request)
    {
        try {
            Candidate::create([
                'name' => $request->name,
                'image' => $request->file('image')->store('candidates', 'public'),
                'chairman' => $request->chairman,
                'vice_chairman' => $request->vice_chairman,
                'vision' => $request->vision,
                'mission' => $request->mission,
                'sort_order' => $request->sort_order,
            ]);

            return redirect()->route('app.candidate.index')->with('succes', 'Candidate updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update candidate, ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $candidate = Candidate::findOrFail($id);

        return view('pages.app.candidate.show', compact('candidate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $candidate = Candidate::findOrFail($id);

        return view('pages.app.candidate.edit', compact('candidate')); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CandidateUpdateRequest $request, string $id)
    {
        try {
            $candidate = Candidate::findOrFail($id);
            
            $candidate->update([
                'name' => $request->name,
                'image' => $request->file('image') ? $request->file('image')->store('candidate', 'public') : $candidate->image,
                'chairman' => $request->chairman,
                'vice_chairman' => $request->vice_chairman,
                'vision' => $request->vision,
                'mission' => $request->mission,
                'sort_order' => $request->sort_order,
            ]);

            return redirect()->route('app.candidate.index')->with('succes', 'Candidate created successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create candidate, ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $candidate = Candidate::findOrFail($id);
            
            $candidate->delete();

            Candidate::where('sort_order', '>', $candidate->sort_order)->decrement('sort_order');

            return redirect()->route('app.candidate.index')->with('succes', 'Candidate deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete candidate, ' . $e->getMessage()]);
        }
    }
}
