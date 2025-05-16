<?php
namespace Lai3221\LaravelWise;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ProxyValidator
{
    /**
     * Verify if the provided proxy corresponds with the decrypted API key from the database.
     *
     * @param string $proxy The proxy value to use as lookup
     * @param string $expectedApiKey The API key to compare against the decrypted value
     * @return bool Returns true when verification passes or when verification is disabled via env
     */
    public static function verifyProxyWithApiKey(string $proxy, string $expectedApiKey): bool
    {
        // Skip verification if disabled in env
        if (!env('VERIFY_PROXY', false)) {
            return true;
        }
        // Fetch the encrypted API key from the accounts table by matching on the proxy.
        $encryptedFinalApiKey = DB::table('accounts')
            ->where('proxy', $proxy)
            ->value('api_client_secret');
        if (!$encryptedFinalApiKey) {
            return false;
        }
        try {
            $decryptedApiKey = Crypt::decryptString($encryptedFinalApiKey);
            return $decryptedApiKey === $expectedApiKey;
        } catch (\Exception $e) {
            return false;
        }
    }
}
