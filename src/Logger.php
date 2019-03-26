<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger;

use Illuminate\Database\Eloquent\Model;
use Sourcetoad\Logger\Enums\ActivityType;
use Sourcetoad\Logger\Enums\HttpVerb;
use Sourcetoad\Logger\Models\AuditActivity;
use Sourcetoad\Logger\Models\AuditKey;
use Sourcetoad\Logger\Models\AuditRoute;

class Logger
{
    public function logSuccessfulLogin(Model $entity)
    {
        $type = ActivityType::SUCCESSFUL_LOGIN;

        // Set generic array of information utilized in this lookup.
        $keys = [
            'email'         => '',
            'password_hash' => '',
        ];

        return $this->logActivity($type, $keys, $entity);
    }

    public function logExplicitLogout(Model $entity)
    {
        $type = ActivityType::FAILED_LOGIN;

        $keys = [
            'id'    => '',
            'email' => '',
        ];

        return $this->logActivity($type, $keys, $entity);
    }

    public function logFailedLogin(Model $entity = null)
    {
        $type = ActivityType::FAILED_LOGIN;

        $keys = [];

        return $this->logActivity($type, $keys, $entity);
    }

    public function logLockedLogin(Model $model = null)
    {
        $type = ActivityType::LOCKED_OUT;

        $keys = [];

        return $this->logActivity($type, $keys, $model);
    }

    public function logPasswordReset(Model $model)
    {
        $type = ActivityType::PASSWORD_CHANGE;

        $keys = [
            'password_hash' => ''
        ];

        return $this->logActivity($type, $keys, $model);
    }

    public function logActivity(int $type, array $keys = [], Model $entity = null)
    {
        $path = \Request::path();

        $morphableType = $morphableId = null;
        if (! empty($entity)) {
            $morphableType = $entity->getMorphClass();
            $morphableId = $entity->getKey();
        }

        $data = [
            'route_id'    => AuditRoute::createOrFind($path)->id,
            'key_id'      => AuditKey::createOrFind($keys)->id,
            'user_id'     => \Request::user()->id ?? null,
            'entity_type' => $morphableType,
            'entity_id'   => $morphableId,
            'type'        => $type,
            'ip_address'  => \Request::ip(),
            'verb'        => $this->getHttpVerb(\Request::method()),
        ];

        return AuditActivity::query()->create($data);
    }

    //--------------------------------------------------------------------------------------------------------------
    // Private functions
    //--------------------------------------------------------------------------------------------------------------

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