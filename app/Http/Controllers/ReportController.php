<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Models\Event;
use App\Models\Report;
use App\Models\Stub;
use App\Models\Source;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Response;

class ReportController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')
            ->except('index', 'show');
        $this->authorizeResource(Report::class, 'report');
    }

    /**
     * Return all the reports
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $reports = Report::with('event')->paginate(20);

        return inertia('Report/Index', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $events = Event::select(['id', 'name', 'date'])->get();
        $tags = Tag::select('name')->get()->pluck('name');
        return inertia('Report/Create', compact('events', 'tags'));
    }

    /**
     * Show the form for creating a new Report from Stub audio.
     *
     * @return \Inertia\Response
     */
    public function createFromStub(Event $event, Source $source, Stub $stub)
    {
        $stub->load('source.event');
        $tags = Tag::select('name')->get()->pluck('name');
        return inertia('Report/Transcribe', compact('stub', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Inertia\Response
     */
    public function store(StoreReportRequest $request)
    {
        $event = Event::findOrFail($request->event_id);
        $report = $event->reports()->create($request->validated());

        foreach ($request->dialogues as $i => $d) {
            $d['order'] = $i;
            $report->dialogues()->create($d);
        }

        foreach ($request->tags as $t) {
            $tag = Tag::where('name', $t)->firstOrFail();
            $report->tags()->attach($tag);
        }

        if ($request->stub_id !== null) {
            Stub::findOrFail($request->stub_id)->attach($report);
        }

        return to_route('report.show', compact('report'))->with('flash', ['message'=> 'Report successfully created!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report): Response
    {
        return inertia('Report/Show', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Inertia\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Inertia\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Inertia\Response
     */
    public function destroy(Report $report)
    {
        // Delete report
        // Delete any stubs that may be associated
    }
}
