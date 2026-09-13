<?php

namespace Plugin\TlcommerceCore\Support;

class OrderNowDisplayConfig
{
    /**
     * Get Order Now display settings.
     */
    public static function get(): array
    {
        $defaults = [
            'enabled' => true,
            'text' => '',
        ];

        $path = self::path();

        if (!is_file($path)) {
            return $defaults;
        }

        $contents = @file_get_contents($path);

        if ($contents === false || trim($contents) === '') {
            return $defaults;
        }

        $data = json_decode($contents, true);

        if (!is_array($data)) {
            return $defaults;
        }

        return [
            'enabled' => array_key_exists('enabled', $data)
                ? (bool) $data['enabled']
                : true,

            'text' => isset($data['text'])
                ? trim((string) $data['text'])
                : '',
        ];
    }

    /**
     * Save Order Now display settings.
     */
    public static function save(array $data): bool
    {
        $path = self::path();
        $directory = dirname($path);

        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true) && !is_dir($directory)) {
                throw new \RuntimeException(
                    'Unable to create Order Now settings directory: ' . $directory
                );
            }
        }

        $payload = [
            'enabled' => !empty($data['enabled']),
            'text' => trim((string) ($data['text'] ?? '')),
        ];

        $json = json_encode(
            $payload,
            JSON_PRETTY_PRINT |
            JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES
        );

        if ($json === false) {
            throw new \RuntimeException(
                'Unable to encode Order Now settings.'
            );
        }

        $written = file_put_contents(
            $path,
            $json,
            LOCK_EX
        );

        if ($written === false) {
            throw new \RuntimeException(
                'Unable to write Order Now settings file: ' . $path
            );
        }

        return true;
    }

    /**
     * Tenant-specific settings file.
     */
    protected static function path(): string
    {
        $tenantKey = null;

        try {
            if (function_exists('tenant')) {
                $tenantKey = tenant('id');
            }
        } catch (\Throwable $e) {
            $tenantKey = null;
        }

        if (empty($tenantKey)) {
            try {
                $tenantKey = request()->getHost();
            } catch (\Throwable $e) {
                $tenantKey = 'default';
            }
        }

        $tenantKey = preg_replace(
            '/[^A-Za-z0-9_-]/',
            '_',
            (string) $tenantKey
        );

        if (empty($tenantKey)) {
            $tenantKey = 'default';
        }

        return storage_path(
            'app/tlcommerce/order-now/' .
            $tenantKey .
            '.json'
        );
    }
}