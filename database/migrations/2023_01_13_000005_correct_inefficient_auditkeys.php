<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Sourcetoad\Logger\Helpers\DataArrayParser;
use Sourcetoad\Logger\Models\AuditKey;

return new class extends Migration
{
    public function up(): void
    {
        AuditKey::query()->chunkById(1000, function (Collection $auditKeys) {
            $auditKeys->each(function (AuditKey $auditKey) {
                // Since these are already keys. We cannot pass it through our existing functions.
                $oldData = (array)json_decode($auditKey->data);

                // Since our code expects data, to obtain keys from. We must trick our existing keys to be values.
                $flippedData = array_flip($oldData);

                $parsed = DataArrayParser::dedupe($flippedData);
                $jsonBlob = json_encode($parsed);
                $jsonMd5 = md5($jsonBlob);

                if (! hash_equals($auditKey->hash, $jsonMd5)) {
                    /** @var AuditKey|null $existingAuditKey */
                    $existingAuditKey = AuditKey::query()
                        ->where('hash', $jsonMd5)
                        ->first();

                    // If we find one. We must take the auditKey id and remove it everywhere for the new one.
                    // Then delete the existing.
                    if ($existingAuditKey) {
                        DB::table('audit_activities')
                            ->where('key_id', $auditKey->id)
                            ->update(['key_id' => $existingAuditKey->id]);

                        DB::table('audit_changes')
                            ->where('key_id', $auditKey->id)
                            ->update(['key_id' => $existingAuditKey->id]);

                        AuditKey::withoutEvents(fn() => $auditKey->delete());
                    } else {
                        // If we don't - we will update this record to change hash/blob and record the id/hash to prevent future queries during migration
                        $auditKey->data = $jsonBlob;
                        $auditKey->hash = $jsonMd5;

                        AuditKey::withoutEvents(fn() => $auditKey->saveOrFail());
                    }
                }
            });
        });
    }

    public function down(): void
    {
        // Reversing this migration is not supported due to 1 way changes.
    }
};
