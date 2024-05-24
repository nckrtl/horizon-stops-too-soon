<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LongRunningJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public \App\Models\LongRunningJob $jobModel)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        for ($i = 0; $i < $this->jobModel->total; $i++) {
            $this->jobModel->update([
                'progress' => $this->jobModel->progress + 1,
            ]);
            sleep(1);
        }
    }
}
