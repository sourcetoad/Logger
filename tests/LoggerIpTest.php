<?php

declare(strict_types=1);

namespace Sourcetoad\Logger\Test;

use Sourcetoad\Logger\Models\AuditActivity;

class LoggerIpTest extends TestCase
{
    public function test_ip_v4(): void
    {
        $address = '127.0.0.1';

        $model = new AuditActivity;
        $model->ip_address = $address;

        $this->assertEquals($address, $model->ip_address);
    }

    public function test_ip_v46(): void
    {
        $address = 'FE80:CD00:0:CDE:1257:0:211E:729C';

        $model = new AuditActivity;
        $model->ip_address = $address;

        $this->assertEquals($address, $model->ip_address);
    }

    public function test_ip_v46_shorthand(): void
    {
        $address = '0000:0000:0000:0000:0000:0000:0000:0000';
        $expected = '::';

        $model = new AuditActivity;
        $model->ip_address = $address;

        $this->assertEquals($expected, $model->ip_address);
    }
}
