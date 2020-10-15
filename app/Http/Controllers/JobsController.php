<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Job;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    public function index()
    {
        $jobs = Job::with('company')->get();
        return response()->json($this->definitionManyResponse($jobs));
    }

    public function store(Request $request)
    {
        $job = new Job();

        if(!$job)
            return response()->json(['message' => 'Register not found.'], 404);

        $job->fill($this->parseRequest($request->all()));

        if (!$job->save())
            return response()->json(['message' => 'Failed to register.'], 500);

        $job->getCurrentCompany();

        return response()->json($this->definitionResponse($job), 201);
    }

    public function show($uuid)
    {
        $job = Job::findByUuid($uuid);

        if (!$job)
            return response()->json(['message' => 'Record not found'], 404);

        return response()->json($this->definitionResponse($job), 200);
    }

    public function update(Request $request, $uuid)
    {
        $job = Job::findByUuid($uuid);

        if(!$job)
            return response()->json(['message' => 'Register not found.'], 404);

        $job->fill($this->parseRequest($request->all()));

        if (!$job->save())
            return response()->json(['message' => 'Failed to update.'], 500);

        $job->getCurrentCompany();

        return response()->json($this->definitionResponse($job), 200);
    }

    public function destroy($uuid)
    {
        $job = Job::findByUuid($uuid);
        try {
            $job->delete();
            return response()->json(['message' => 'Successfully deleted'], 200);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()], $err->getCode());
        }
    }

    private function parseRequest(array $jobRequest, bool $update = false)
    {
        if (!$update)
            return [
                'title'         => $jobRequest['titleJob'],
                'description'   => $jobRequest['descriptionJob'],
                'local'         => $jobRequest['localJob'],
                'remote'        => $jobRequest['remoteJob'],
                'type'          => $jobRequest['typeJob'],
                'company_id'    => (Company::findByUuid($jobRequest['companyJob']))->id
            ];

        $items = [
            'title'         => $jobRequest['titleJob'],
            'description'   => $jobRequest['descriptionJob'],
            'local'         => $jobRequest['localJob'],
            'remote'        => $jobRequest['remoteJob'],
            'type'          => $jobRequest['typeJob'],
        ];

        if (isset($jobRequest['companyJob']))
            $items['company_id'] = (Company::findByUuid($jobRequest['companyJob']))->id;

        return  $items;
    }

    private function definitionResponse(Job $job): array
    {
        return [
            'titleJob'          => $job->title,
            'descriptionJob'    => $job->description,
            'localJob'          => $job->local,
            'remoteJob'         => $job->remote,
            'typeJob'           => $job->type,
            'companyJob'           => [
                'nameCompany'       => $job->company->name,
                'emailCompany'      => $job->company->email,
                'websiteCompany'    => $job->company->website,
                'logoCompany'       => $job->company->logo,
            ],
        ];
    }

    private function definitionManyResponse(object $jobs): array
    {
        $items = [];
        foreach ($jobs as $job){
            array_push($items, $this->definitionResponse($job));
        }
        return $items;
    }
}
