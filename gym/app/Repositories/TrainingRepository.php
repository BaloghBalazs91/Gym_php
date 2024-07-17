<?php

namespace App\Repositories;

use App\Models\Training;
use App\Services\WeekCalculator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TrainingRepository
{
    public function __construct(protected WeekCalculator $weekCalculator)
    {}

    public function getAll()
    {
        $trainings = Training::with(['trainingMethod', 'trainer', 'trainees' => function ($query) {
            $query->select('users.id');
        }])
            ->withCount('trainees')
            ->whereNotNull('trainer_id')
            ->where('start', '>=', Carbon::now()->setTimezone('GMT+2'))
            ->orderBy('start')
            ->get();

        return $trainings;
    }


    public function getTrainingsByWeek(Request $request) {
        $week = $request->query('week');
        Log::info($week);

        if ($week == null)
        {
            $trainings = Training::with(['trainingMethod', 'trainer', 'trainees' => function ($query) {
                $query->select('users.id');
            }])
                ->withCount('trainees')
                ->whereNotNull('trainer_id')
                ->where('start', '>=', Carbon::now()->setTimezone('GMT+2'))
                ->orderBy('start')
                ->get();

            //return response()->json($trainings);
        }

        else
        {
//            $currentDate = Carbon::now();
//            $startOfWeek = $currentDate->addWeeks((int)$week)->startOfWeek()->startOfDay()->toDateTimeString();
//            $endOfWeek = $currentDate->endOfWeek()->endOfDay()->toDateTimeString();

            $period = $this->weekCalculator->calculateWeek((int)$week);


            $trainings = Training::whereBetween('start', $period)
                ->with(['trainingMethod', 'trainer', 'trainees' => function ($query) {
                    $query->select('users.id');
                }])
                ->withCount('trainees')
                ->whereNotNull('trainer_id')
                ->where('start', '>=', Carbon::now()->setTimezone('GMT+2'))
                ->orderBy('start')
                ->get();
        }

        return [$trainings, $period];
    }
}
