<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CompaniesController extends Controller
{
    public function index()
    {
        $companies = Company::all();
        return response()->json($this->definitionManyResponse($companies));
    }

    public function store(Request $request)
    {
        $company = new Company();

        if (!$company)
            return response()->json(['message' => 'Register not found.'], 404);

        $company->fill($this->parseRequest($request->all()));

        if (!$company->save())
            return response()->json(['message' => 'Failed to register.'], 500);

        return response()->json($this->definitionResponse($company), 201);
    }

    public function show($uuid)
    {

        $company = Company::findByUuid((string)$uuid);

        if (!$company)
            return response()->json(['message' => 'Register not found.'], 404);

        return response()->json($this->definitionResponse($company));
    }

    public function update(Request $request, $uuid)
    {
        $company = Company::findByUuid($uuid);

        if (!$company)
            return response()->json(['message' => 'Register not found.'], 404);

        $company->fill($this->parseRequest($request->all(), true));

        if (!$company->save())
            return response()->json(['message' => 'Failed to update.'], 500);

        return response()->json($this->definitionResponse($company), 200);
    }

    public function destroy($uuid)
    {
        $company = Company::findByUuid($uuid);
        try {
            $company->delete();
            return response()->json(['message' => 'Successfully deleted'], 200);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()], $err->getCode());
        }
    }

    private function parseRequest(array $companyRequest, bool $update = false)
    {
        if (!$update)
            return [
                'name'      => $companyRequest['nameCompany'],
                'email'     => $companyRequest['emailCompany'],
                'website'   => $companyRequest['websiteCompany'],
                'logo'      => $companyRequest['logoCompany'],
                'password'  => Hash::make($companyRequest['passwordCompany'])
            ];

        $items = [
            'name'      => $companyRequest['nameCompany'],
            'email'     => $companyRequest['emailCompany'],
            'website'   => $companyRequest['websiteCompany'],
            'logo'      => $companyRequest['logoCompany'],
        ];

        if (isset($companyRequest['passwordCompany']))
            $items['password'] = Hash::make($companyRequest['passwordCompany']);

        return $items;
    }

    private function definitionResponse(Company $company): array
    {
        return [
            'nameCompany'       => $company->name,
            'emailCompany'      => $company->email,
            'websiteCompany'    => $company->website,
            'logoCompany'       => $company->logo,
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
