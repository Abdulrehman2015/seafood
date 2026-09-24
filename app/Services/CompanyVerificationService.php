<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;

class CompanyVerificationService
{
    /**
     * Common legal entity terms and noise words in business names (Malaysia & International).
     */
    protected static array $suffixes = [
        'sendirian berhad', 'sdn. bhd.', 'sdn bhd', 'sdn.bhd.', 'sdn.bhd', 'sdnbhd', 'sdn', 'bhd.', 'bhd',
        'berhad', 'pte. ltd.', 'pte ltd', 'pte.ltd.', 'pteltd', 'ltd.', 'ltd', 'limited',
        'enterprise', 'trading', 'holdings', 'holding', 'group', 'corporation', 'corp', 'corp.',
        'co.', 'co', 'company', 'inc.', 'inc', 'llc', 'llp', 'pllc', 'partners', 'resources',
        'solutions', 'services', 'supply', 'supplies', 'ventures', 'venture'
    ];

    /**
     * Clean and normalize a company name for fuzzy matching.
     */
    public static function normalizeCompanyName(?string $name): string
    {
        if (empty($name)) {
            return '';
        }

        $clean = mb_strtolower(trim($name), 'UTF-8');

        // Remove known corporate terms
        foreach (self::$suffixes as $suffix) {
            $clean = preg_replace('/\b' . preg_quote($suffix, '/') . '\b/i', '', $clean);
        }

        // Remove all non-alphanumeric characters
        $clean = preg_replace('/[^a-z0-9]/i', '', $clean);

        return trim($clean);
    }

    /**
     * Normalize an SSM / Business Registration number.
     * e.g. "202301012345 (1234567-X)" -> "2023010123451234567X"
     */
    public static function normalizeSsm(?string $ssm): string
    {
        if (empty($ssm)) {
            return '';
        }

        return strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $ssm));
    }

    /**
     * Check if a company name is the same or very similar to an existing registered company.
     * Non-blocking: returns matching details and user-friendly alert message.
     */
    public function checkSimilarity(string $companyName, ?int $excludeUserId = null): array
    {
        $inputRaw = trim($companyName);
        $inputNorm = self::normalizeCompanyName($inputRaw);

        if (mb_strlen($inputNorm) < 3) {
            return [
                'has_similarity'     => false,
                'matched_company'    => null,
                'similarity_percent' => 0,
                'message'            => null,
            ];
        }

        $query = User::whereNotNull('company_name')
            ->where('company_name', '!=', '');

        if ($excludeUserId) {
            $query->where('id', '!=', $excludeUserId);
        }

        $existingCompanies = $query->pluck('company_name', 'id');

        $bestMatch = null;
        $highestSimilarity = 0;

        foreach ($existingCompanies as $id => $existingRaw) {
            $existingNorm = self::normalizeCompanyName($existingRaw);

            if (empty($existingNorm)) {
                continue;
            }

            // 1. Exact normalized match (e.g. "MST Seafood Sdn Bhd" vs "mst seafood trading")
            if ($inputNorm === $existingNorm) {
                return [
                    'has_similarity'     => true,
                    'matched_company'    => $existingRaw,
                    'similarity_percent' => 100,
                    'message'            => __t('auth.company_similarity_warning', 'This company may already be registered. Please check if your company already has an account or contact MST.'),
                ];
            }

            // 2. Substring containment for significant length
            if (mb_strlen($inputNorm) >= 4 && mb_strlen($existingNorm) >= 4) {
                if (str_contains($inputNorm, $existingNorm) || str_contains($existingNorm, $inputNorm)) {
                    $ratio = min(mb_strlen($inputNorm), mb_strlen($existingNorm)) / max(mb_strlen($inputNorm), mb_strlen($existingNorm)) * 100;
                    if ($ratio >= 70 && $ratio > $highestSimilarity) {
                        $highestSimilarity = $ratio;
                        $bestMatch = $existingRaw;
                    }
                }
            }

            // 3. Levenshtein / Similar text
            similar_text($inputNorm, $existingNorm, $percent);
            if ($percent >= 80 && $percent > $highestSimilarity) {
                $highestSimilarity = $percent;
                $bestMatch = $existingRaw;
            }
        }

        if ($highestSimilarity >= 80 && $bestMatch) {
            return [
                'has_similarity'     => true,
                'matched_company'    => $bestMatch,
                'similarity_percent' => round($highestSimilarity, 1),
                'message'            => __t('auth.company_similarity_warning', 'This company may already be registered. Please check if your company already has an account or contact MST.'),
            ];
        }

        return [
            'has_similarity'     => false,
            'matched_company'    => null,
            'similarity_percent' => 0,
            'message'            => null,
        ];
    }

    /**
     * Check if an SSM number is already registered (Strict Uniqueness Check).
     */
    public function checkSsmUniqueness(string $ssmNumber, ?int $excludeUserId = null): array
    {
        $raw = trim($ssmNumber);
        $norm = self::normalizeSsm($raw);

        if (empty($norm)) {
            return ['is_duplicate' => false, 'matched' => null, 'message' => null];
        }

        $query = User::whereNotNull('company_reg_no')
            ->where('company_reg_no', '!=', '');

        if ($excludeUserId) {
            $query->where('id', '!=', $excludeUserId);
        }

        // Check direct exact match
        $existing = (clone $query)->where('company_reg_no', $raw)->first();

        // If not found, check normalized match
        if (!$existing) {
            $all = $query->get(['id', 'company_name', 'company_reg_no']);
            foreach ($all as $user) {
                if (self::normalizeSsm($user->company_reg_no) === $norm) {
                    $existing = $user;
                    break;
                }
            }
        }

        if ($existing) {
            return [
                'is_duplicate' => true,
                'matched'      => $existing->company_reg_no,
                'company_name' => $existing->company_name,
                'message'      => __t('auth.ssm_already_registered', 'An account with this Company Registration Number (SSM: :ssm) is already registered. Please check if your company already has an account or contact MST.', ['ssm' => $raw]),
            ];
        }

        return ['is_duplicate' => false, 'matched' => null, 'message' => null];
    }

    /**
     * Check if an email is already registered (Strict Uniqueness Check).
     */
    public function checkEmailUniqueness(string $email, ?int $excludeUserId = null): array
    {
        $email = strtolower(trim($email));

        if (empty($email)) {
            return ['is_taken' => false, 'message' => null];
        }

        $query = User::where('email', $email);

        if ($excludeUserId) {
            $query->where('id', '!=', $excludeUserId);
        }

        if ($query->exists()) {
            return [
                'is_taken' => true,
                'message'  => __t('auth.email_already_registered', 'An account with this email already exists. Please Sign In or reset your password.'),
            ];
        }

        return ['is_taken' => false, 'message' => null];
    }
}
