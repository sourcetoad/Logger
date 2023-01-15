<?php

namespace Sourcetoad\Logger\Commands;

use Illuminate\Console\Command;
use Sourcetoad\Logger\Helpers\AuditResolver;
use Sourcetoad\Logger\Models\AuditChange;
use Sourcetoad\Logger\Models\AuditModel;

class AuditModelResolver extends Command
{
    protected $signature = 'logger:audit-resolver';
    protected $description = 'Identifiers users associated with models/changes logged.';

    public function handle(): void
    {
        AuditChange::query()->where('processed', false)->chunkById(200, function ($items) {
            /** @var AuditChange $item */
            foreach ($items as $item) {
                $id = AuditResolver::findUserId($item->entity);
                $item->processed = true;
                $item->user_id = $id;
                $item->saveOrFail();
            }
        });

        AuditModel::query()->where('processed', false)->chunkById(200, function ($items) {
            /** @var AuditModel $item */
            foreach ($items as $item) {
                $id = AuditResolver::findUserId($item->entity);
                $item->processed = true;
                $item->user_id = $id;
                $item->saveOrFail();
            }
        });
    }
}
