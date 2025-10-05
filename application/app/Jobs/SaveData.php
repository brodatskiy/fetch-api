<?php

namespace App\Jobs;

use App\Enum\Model;
use App\Models\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use Throwable;

class SaveData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    private int $accountId;
    private Model $model;
    private array $data;

    /**
     * Create a new job instance.
     *
     * @param int $accountId
     * @param Model $model
     * @param array $data
     */
    public function __construct(int $accountId, Model $model, array $data)
    {
        $this->accountId = $accountId;
        $this->model = $model;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $model = app($this->model->fullClass());
        $account = Account::find($this->accountId);

        foreach ($this->data as $item) {
            $entity = $model::updateOrCreate($item, $item);
            $account->resolveRelation($this->model->value)->syncWithoutDetaching([$entity->id]);
        }
    }

    public function failed(Throwable $exception): void
    {
        Log::error('SaveData job failed', [
            'account_id' => $this->accountId,
            'model' => $this->model,
            'error' => $exception->getMessage()
        ]);
    }
}
