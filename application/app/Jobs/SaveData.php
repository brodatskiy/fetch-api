<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $model;
    private array $data;

    /**
     * Create a new job instance.
     *
     * @param string $model
     * @param array $data
     */
    public function __construct(string $model, array $data)
    {
        $this->model = $model;
        $this->data = $data['data'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $model = app($this->model);

        foreach ($this->data as $item) {
            $model::create($item);
        }
    }
}
