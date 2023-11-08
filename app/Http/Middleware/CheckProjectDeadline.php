<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Project;
use Carbon\Carbon;

class CheckProjectDeadline
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $project = $request->route('project');

        if (is_numeric($project)) {
            $project = Project::findOrFail($project);
        }

        if (Carbon::parse($project->deadline)->isPast()) {
            if ($project->status !== 'completed') {
                $project->status = 'completed';
                $project->save();

                return response()->json([
                    'message' => 'The project deadline has passed and it has been marked as completed.'
                ], 403);
            } else {
                return response()->json([
                    'message' => 'This project is already completed.'
                ], 403);
            }
        }

        return $next($request);
    }
}
