<?php

namespace App\Http\Controllers\Integration\Google;

use App\Step;
use Carbon\Carbon;

/**
 * Class GoogleFit.
 */
class GoogleFit extends Google
{
    /**
     * @var null
     */
    private $dataSourceId = null;

    /**
     * @var string
     */
    private $userId = 'me';

    /**
     * @var int
     */
    public $externalApiLimit = 86400;

    /**
     * @var string
     */
    public $externalApiLimitInterval = 'Day';

    /**
     * @var string
     */
    public $externalApiName = 'GoogleFitApi';

    /**
     * GoogleFit constructor.
     */
    public function __construct()
    {
        $scopes = [
            'https://www.googleapis.com/auth/fitness.activity.read',
            'https://www.googleapis.com/auth/fitness.body.read',
            'https://www.googleapis.com/auth/fitness.location.read',
        ];
        parent::__construct($scopes);

        return $this->fitness_service = new \Google_Service_Fitness($this->google_client);
    }

    /**
     * @return \Google_Service_Fitness_UsersDataSourcesDatasets_Resource
     */
    private function getDataSourcesAndSets()
    {
        return $this->fitness_service->users_dataSources_datasets;
    }

    /**
     * @param int $days
     *
     * @return \Google_Service_Fitness_Dataset
     */
    private function getDataSetsFromDataSource($days)
    {
        $startTime = Carbon::now()->subDays($days)->hour(0)->minute(0)->second(0);
        $endTime = Carbon::now()->minute(0)->second(0);

        $GetFitData = $this->getDataSourcesAndSets()->get(
            $this->userId,
            $this->dataSourceId,
            $startTime->timestamp.'000000000'.'-'.$endTime->timestamp.'000000000'
        );

        $this->incrementGoogleFitApiLimitCounter();

        return $GetFitData;
    }

    public function getStepData($days = 2)
    {
        $this->dataSourceId = 'derived:com.google.step_count.delta:com.google.android.gms:estimated_steps';

        $listDataSets = $this->getDataSetsFromDataSource($days);

        $dataArray = [];
        $dateArray = [];
        $i = 0;
        $dataSet = '';

        while ($listDataSets->valid()) {
            if ($i == 0) {
                $dataSet = $listDataSets->current();
            }
            if (array_key_exists('originDataSourceId', $dataSet) && $dataSet['originDataSourceId'] === null) {
                continue;
            }

            $i++;
            $step_count = 0;

            $startTimeNanos = $this->convertNanosToDateTime($dataSet['startTimeNanos']);
            $endTimeNanos = $this->convertNanosToDateTime($dataSet['endTimeNanos']);
            $duration = $startTimeNanos->diffInSeconds($endTimeNanos);
            $dataSetValues = $dataSet['value'];
            if ($dataSetValues && is_array($dataSetValues)) {
                foreach ($dataSetValues as $dataSetValue) {
                    $step_count += $dataSetValue['intVal'];
                }
            }
            $step_id = $startTimeNanos->minute(0)->second(0)->format('Ymd');
            if (!isset($dataArray[$step_id])) {
                $dataArray[$step_id] = [
                    'date'     => $startTimeNanos->toDateString(),
                    'steps'    => $step_count,
                    'duration' => $duration,
                ];
                $dateArray[] = $startTimeNanos;
            } else {
                $dataArray[$step_id]['steps'] += $step_count;
                $dataArray[$step_id]['duration'] += $duration;
            }
            $dataSet = $listDataSets->next();
        }

        return array_map([$this, 'storeStep'], $dataArray);
    }

    private function storeStep($stepArray)
    {
        $step = Step::firstOrNew(
            ['date' => $stepArray['date']]
        );

        $step->steps = $stepArray['steps'];
        $step->duration = $stepArray['duration'];

        return $step->save();
    }

    /**
     * @param $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @param $dataSourceId
     */
    public function setDataSourceId($dataSourceId)
    {
        $this->dataSourceId = $dataSourceId;
    }

    public function incrementGoogleFitApiLimitCounter()
    {
        $this->addExternalAPILimitCounter(
            Carbon::now(),
            $this->externalApiName,
            $this->externalApiLimit,
            $this->externalApiLimitInterval
        );
    }
}
