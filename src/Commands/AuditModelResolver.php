<?php

namespace Sourcetoad\Logger\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Sourcetoad\Logger\Logger;
use Sourcetoad\Logger\Helpers\AuditResolver;
use Sourcetoad\Logger\LoggerServiceProvider;
use Sourcetoad\Logger\Models\AuditChange;
use Sourcetoad\Logger\Models\AuditModel;

class AuditModelResolver extends Command
{
    protected $signature = 'logger:audit-resolver';
    protected $description = 'Identifiers users associated with models/changes logged.';

    public function handle(): void
    {
        AuditChange::query()
            ->where('processed', false)
            ->with(['entity' => function (MorphTo $morphTo) {
                $morphTo->morphWith($this->getOwnerMorphs());
            }])
            ->chunkById(200, function ($items) {
                /** @var AuditChange[] $items */
                foreach ($items as $item) {
                    $owner = AuditResolver::findOwner($item->entity);

                    $item->processed = true;
                    $item->owner_id = $owner?->getKey();
                    $item->owner_type = !is_null($owner) ? Logger::getNumericMorphMap($owner) : null;
                    $item->saveOrFail();
                }
            });

        AuditModel::query()
            ->where('processed', false)
            ->with(['entity' => function (MorphTo $morphTo) {
                $morphTo->morphWith($this->getOwnerMorphs());
            }])
            ->chunkById(200, function ($items) {
                /** @var AuditModel[] $items */
                foreach ($items as $item) {
                    $owner = AuditResolver::findOwner($item->entity);

                    $item->processed = true;
                    $item->owner_id = $owner?->getKey();
                    $item->owner_type = !is_null($owner) ? Logger::getNumericMorphMap($owner) : null;
                    $item->saveOrFail();
                }
            });
    }

    /**
     * @return array<class-string, string[]>
     */
    private function getOwnerMorphs(): array
    {
        return collect(LoggerServiceProvider::$morphs)->mapWithKeys(fn(string $class) => [
            $class => array_filter([(new $class)->getOwnerRelationshipName()]),
        ])->toArray();
    }
}
