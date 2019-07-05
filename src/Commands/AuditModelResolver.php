<?php

namespace Sourcetoad\Logger\Commands;

use Illuminate\Console\Command;
use Sourcetoad\Logger\Models\AuditChange;
use Sourcetoad\Logger\Models\AuditModel;

class AuditModelResolver extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logger:audit-resolver';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Identifiers users associated with models/changes logged.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        AuditChange::query()->where('processed', false)->chunkById(200, function ($items) {
            /** @var AuditChange $item */
            foreach ($items as $item) {
                if (empty($item->entity)) {
                    $item->processed = true;
                    $item->saveOrFail();
                    continue;
                }

                $id = $item->entity->trackableUserResolver();

                if (empty($id)) {
                    $this->error(get_class($item->entity) . ' - ' . $item->entity_id . ' could not be resolved to a user_id.');
                    continue;
                }

                $item->processed = true;
                $item->user_id = $id;
                $item->saveOrFail();
            }
        });

        AuditModel::query()->where('processed', false)->chunkById(200, function ($items) {
            /** @var AuditModel $item */
            foreach ($items as $item) {
                if (empty($item->entity)) {
                    $item->processed = true;
                    $item->saveOrFail();
                    continue;
                }

                $id = $item->entity->trackableUserResolver();

                if (empty($id)) {
                    $this->error(get_class($item->entity) . ' - ' . $item->entity_id . ' could not be resolved to a user_id.');
                    continue;
                }

                $item->processed = true;
                $item->user_id = $id;
                $item->saveOrFail();
            }
        });
    }
}
