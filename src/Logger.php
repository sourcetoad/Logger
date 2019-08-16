<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger;

use Illuminate\Database\Eloquent\Model;
use Sourcetoad\Logger\Enums\ActivityType;
use Sourcetoad\Logger\Enums\HttpVerb;
use Sourcetoad\Logger\Helpers\AuditResolver;
use Sourcetoad\Logger\Models\AuditActivity;
use Sourcetoad\Logger\Models\AuditChange;
use Sourcetoad\Logger\Models\AuditKey;
use Sourcetoad\Logger\Models\AuditModel;
use Sourcetoad\Logger\Models\AuditRoute;

class Logger
{
    /** @var Model[] */
    static $retrievedModels = [];

    /** @var array */
    static $changedModels = [];

    public function logSuccessfulLogin()
    {
        $type = ActivityType::SUCCESSFUL_LOGIN;

        // Set generic array of information utilized in this lookup.
        $keys = [
            'email'         => '',
            'password_hash' => '',
        ];

        return $this->logActivity($type, $keys);
    }

    public function logExplicitLogout()
    {
        $type = ActivityType::LOGOUT;

        $keys = [
            'id'    => '',
            'email' => '',
        ];

        return $this->logActivity($type, $keys);
    }

    public function logFailedLogin()
    {
        $type = ActivityType::FAILED_LOGIN;

        $keys = [];

        return $this->logActivity($type, $keys);
    }

    public function logLockedLogin()
    {
        $type = ActivityType::LOCKED_OUT;

        $keys = [];

        return $this->logActivity($type, $keys);
    }

    public function logPasswordReset()
    {
        $type = ActivityType::PASSWORD_CHANGE;

        $keys = [
            'password_hash' => ''
        ];

        return $this->logActivity($type, $keys);
    }

    public function logRetrievedModel(Model $model): void
    {
        static::$retrievedModels[] = $model;
    }

    public function logChangedModel(Model $model, array $fields): void
    {
        static::$changedModels[] = [
            'model'  => $model,
            'fields' => $fields,
        ];
    }

    public function logActivity(int $type, array $keys = [])
    {
        $path = \Request::path();
        $verb = $this->getHttpVerb(\Request::method());
        $routeId = AuditRoute::createOrFind($path)->id;
        $userId = \Request::user()->id ?? null;
        $keyId = AuditKey::createOrFind($keys)->id;
        $ipAddress = \Request::ip();

        // If we have changed models. Make another entry for the changes.
        if (! empty(self::$changedModels)) {
            $data = [
                'route_id'   => $routeId,
                'key_id'     => $keyId,
                'user_id'    => $userId,
                'type'       => ActivityType::MODIFY_DATA,
                'ip_address' => $ipAddress,
                'verb'       => $verb,
            ];

            /** @var AuditActivity $activity */
            $activity = AuditActivity::query()->create($data);

            $data = [];
            foreach (self::$changedModels as $changed) {
                /** @var Model $model */
                $model = $changed['model'];
                $keys = AuditKey::createOrFind($changed['fields']);

                // We could be given a model that isn't reflective of anything in the database (IE not saved)
                if (empty($model->getKey())) {
                    continue;
                }

                $data[] = [
                    'activity_id' => $activity->id,
                    'entity_type' => $this->getNumericMorphMap($model),
                    'entity_id'   => $model->getKey(),
                    'key_id'      => $keys->id,
                    'processed'   => false,
                ];
            }

            AuditChange::query()->insert($data);
            self::$changedModels = [];
        }

        $data = [
            'route_id'    => $routeId,
            'key_id'      => $keyId,
            'user_id'     => $userId,
            'type'        => $type,
            'ip_address'  => $ipAddress,
            'verb'        => $verb,
        ];

        /** @var AuditActivity $activity */
        $activity = AuditActivity::query()->create($data);

        $data = [];
        foreach (self::$retrievedModels as $model) {

            // We could be given a model that isn't reflective of anything in the database (IE not saved)
            if (empty($model->getKey())) {
                continue;
            }

            $data[] = [
                'activity_id' => $activity->id,
                'entity_type' => $this->getNumericMorphMap($model),
                'entity_id'   => $model->getKey(),
                'processed'   => false,
            ];
        }
        AuditModel::query()->insert($data);
        self::$retrievedModels = [];

        return $activity;
    }

    //--------------------------------------------------------------------------------------------------------------
    // Private functions
    //--------------------------------------------------------------------------------------------------------------

    private function getNumericMorphMap(Model $model): int
    {
        $fcqn = get_class($model);
        $morphMap = LoggerServiceProvider::$morphs;
        $morphableTypeId = null;

        if (! empty($morphMap) && in_array($fcqn, $morphMap)) {
            $morphableTypeId = array_search($fcqn, $morphMap, true);
        }

        if (is_numeric($morphableTypeId)) {
            return $morphableTypeId;
        }

        throw new \InvalidArgumentException(get_class($model) . " has no numeric model map. Check `morphs` in Logger.");
    }

    private function getHttpVerb(string $verb): int
    {
        switch (strtolower($verb)) {
            case 'get':
                return HttpVerb::GET;
            case 'post':
                return HttpVerb::POST;
            case 'patch':
                return HttpVerb::PATCH;
            case 'put':
                return HttpVerb::PUT;
            case 'delete':
                return HttpVerb::DELETE;
            default:
                return HttpVerb::UNKNOWN;
        }
    }
}