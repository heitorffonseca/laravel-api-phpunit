<?php

namespace Tests\Feature;

use App\Models\Company;
use Faker\Factory;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    public function testAllCompanies()
    {
        ($this->get('api/companies'))->assertStatus(200);
    }

    public function testShowCompany()
    {
        $company = Company::findRandom();
        ($this->get("api/companies/{$company->uuid}"))->assertStatus(200);
    }

    public function testStoreCompany()
    {
        ($this->post('api/companies', $this->fakerCompany()))->assertStatus(201);
        ($this->post('api/companies', $this->fakerCompany()))->assertStatus(201);
        ($this->post('api/companies', $this->fakerCompany()))->assertStatus(201);
        ($this->post('api/companies', $this->fakerCompany()))->assertStatus(201);
    }

    public function testUpdateCompany()
    {
        $company = Company::findRandom();
        ($this->put("api/companies/{$company->uuid}", $this->fakerCompany()))->assertStatus(200);
    }

    public function testDestroyCompany()
    {
        $company = Company::findRandom();
        ($this->delete("api/companies/{$company->uuid}"))->assertStatus(200);

    }

    private function fakerCompany()
    {
        $faker = Factory::create();

        return [
            'nameCompany'       => $faker->company,
            'emailCompany'      => $faker->companyEmail,
            'websiteCompany'    => $faker->url,
            'logoCompany'       => $faker->imageUrl(),
            'passwordCompany'   => 'secret'
        ];
    }

}
