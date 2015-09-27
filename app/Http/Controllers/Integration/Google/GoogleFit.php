<?php

namespace App\Http\Controllers\Integration\Google;

use App\Http\Controllers\Api\Activity\StepController;
use App\Http\Controllers\Api\DateTime\DateController;
use Carbon\Carbon;
use App\Http\Controllers\Api\DateTime;

/**
 * Class GoogleFit
 * @package App\Http\Controllers\Integration
 */
class GoogleFit extends Google
{

    private $dataSourceId = null;
    private $userId = 'me';

    /**
     * GoogleFit constructor.
     */
    public function __construct()
    {
        $scopes = [
            'https://www.googleapis.com/auth/fitness.activity.read',
            'https://www.googleapis.com/auth/fitness.body.read',
            'https://www.googleapis.com/auth/fitness.location.read'
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
     * @return \Google_Service_Fitness_Dataset
     */
    private function getDataSetsFromDataSource($days = 2)
    {

        $startTime = Carbon::now()->subDays($days)->hour(0)->minute(0)->second(0);
        $endTime = Carbon::now()->minute(0)->second(0);

        return $this->getDataSourcesAndSets()->get(
            $this->userId,
            $this->dataSourceId,
            $startTime->timestamp . '000000000' . '-' . $endTime->timestamp . '000000000'
        );
    }

    public function getStepData()
    {

        $this->dataSourceId = "derived:com.google.step_count.delta:com.google.android.gms:estimated_steps";

        $listDataSets = $this->getDataSetsFromDataSource();

        $dataArray = array();
        $dateArray = array();
        $i = 0;
        $dataSet = '';

        while ($listDataSets->valid()) {
            if ($i == 0) {
                $dataSet = $listDataSets->current();
            }
            if (array_key_exists('originDataSourceId', $dataSet) && $dataSet['originDataSourceId'] === null) {
                continue;
            }

            $i ++;
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
            $step_id = $startTimeNanos->minute(0)->second(0)->format('YmdH');
            if (!isset($dataArray[$step_id])) {
                $dataArray[$step_id] = array(
                    'date'     => $startTimeNanos->minute(0)->second(0),
                    'steps'    => $step_count,
                    'duration' => $duration
                );
                $dateArray[] = $startTimeNanos;
            } else {
                $dataArray[$step_id]['steps'] += $step_count;
                $dataArray[$step_id]['duration'] += $duration;
            }
            $dataSet = $listDataSets->next();
        }
        $dateController = new DateController;
        $stepController = new StepController;
        $dateController->internalStoreRequest($dateArray);
        $stepController->internalStoreRequest($dataArray);
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

}
