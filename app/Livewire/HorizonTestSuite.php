<?php

namespace App\Livewire;

use App\Models\LongRunningJob;
use Illuminate\Support\Facades\Redis;
use Laravel\Horizon\Contracts\MasterSupervisorRepository;
use Livewire\Component;
use Symfony\Component\Process\Process;

class HorizonTestSuite extends Component
{
    public string $horizonStatus;

    public array $jobModels;

    public string $phpPath;

    public $canShowHorizonControls = false;

    public function __construct()
    {
        $this->phpPath = config('app.php_path');
    }

    public function render(MasterSupervisorRepository $masterSupervisorRepository)
    {
        $this->checkIfHorizonControlsCanBeShown();
        $this->checkHorizonStatus($masterSupervisorRepository);
        $this->updateJobModels();

        return view('livewire.horizon-test-suite');
    }

    public function checkIfHorizonControlsCanBeShown()
    {
        $process = new Process([$this->phpPath, '-v']);
        $process->run();

        $this->canShowHorizonControls = ! empty($process->getOutput());
    }

    public function checkHorizonStatus($masterSupervisorRepository)
    {
        if (! $masters = $masterSupervisorRepository->all()) {
            $this->horizonStatus = 'Inactive';

            return;
        }

        if (collect($masters)->contains(function ($master) {
            return $master->status === 'paused';
        })) {
            $this->horizonStatus = 'Paused';

            return;
        }

        $this->horizonStatus = 'Running';
    }

    public function startHorizon()
    {
        $this->artisan('horizon');
        $this->dispatch('$refresh');
    }

    public function stopHorizon()
    {
        $this->artisan('horizon:terminate');
        $this->dispatch('$refresh');
    }

    public function artisan($command)
    {
        // Absolute path to the Artisan script
        $artisanPath = base_path('artisan');

        // Create the Symfony Process instance
        $process = new Process([$this->phpPath, $artisanPath, $command]);
        $process->setOptions(['create_new_console' => true]);

        // Run the process
        $process->start();
    }

    public function addJob()
    {
        $jobModel = LongRunningJob::create([
            'progress' => 0,
            'total' => 30,
        ]);

        dispatch(new \App\Jobs\LongRunningJob($jobModel));
    }

    public function updateJobModels()
    {
        $this->jobModels = LongRunningJob::all()->toArray();
    }

    public function clearAllJobs()
    {
        LongRunningJob::truncate();

        $this->stopHorizon();
        Redis::command('flushdb');
    }
}
