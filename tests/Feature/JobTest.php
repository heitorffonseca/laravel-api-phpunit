<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Job;
use Faker\Factory;
use Tests\TestCase;

class JobTest extends TestCase
{
    public function testAllJobs()
    {
        ($this->get('api/jobs'))->assertStatus(200);
    }

    public function testShowJob()
    {
        $job = Job::findRandom();
        ($this->get("api/jobs/{$job->uuid}"))->assertStatus(200);
    }

    public function testStoreJob()
    {
        ($this->post('api/jobs', $this->fakerJob()))->assertStatus(201);
        ($this->post('api/jobs', $this->fakerJob()))->assertStatus(201);
        ($this->post('api/jobs', $this->fakerJob()))->assertStatus(201);
        ($this->post('api/jobs', $this->fakerJob()))->assertStatus(201);
    }

    public function testUpdateJob()
    {
        $job = Job::findRandom();
        ($this->put("api/jobs/{$job->uuid}", $this->fakerJob()))->assertStatus(200);
    }

    public function testDestroyJob()
    {
        $job = Job::findRandom();
        ($this->delete("api/jobs/{$job->uuid}"))->assertStatus(200);
    }

    private function fakerJob()
    {
        $faker = Factory::create();
        return [
            'titleJob'          => $faker->jobTitle,
            'descriptionJob'    => $faker->text,
            'localJob'          => 'São Paulo',
            'remoteJob'         => rand(1, 2) == 1 ? 'yes' : 'no',
            'typeJob'           => rand(1, 5),
            'companyJob'        => (Company::findRandom())->uuid,
        ];
    }
}
