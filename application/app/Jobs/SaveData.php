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

    private array $data;
    private int $accountId;
    private string $modelName;


    /**
     * Create a new job instance.
     *
     * @param int $accountId
     * @param string $modelName
     * @param array $data
     */
    public function __construct(int $accountId, string $modelName, array $data)
    {
        $this->accountId = $accountId;
        $this->modelName = $modelName;
        $this->data = $data['data'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $model = app($this->modelName);

        foreach ($this->data as $item) {
            $item['account_id'] = $this->accountId;

            $existing = $model::where($item)->first();

            if ($existing) {
                $existing->update($item);
            } else {
                $model::create($item);
            }
        }
    }
}
