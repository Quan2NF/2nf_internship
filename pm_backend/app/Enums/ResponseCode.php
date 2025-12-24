<?php

namespace App\Enums;

enum ResponseCode: string
{
    // ===== SUCCESS =====
    case SUCCESS = 'R_CMN_200_01';

    // ===== CLIENT ERROR =====
    case INVALID_PARAMETER = 'R_CMN_400_01';

    // ===== AUTH =====
    case UNAUTHORIZED = 'R_CMN_401_01';
    case FORBIDDEN = 'R_CMN_403_01';

    // ===== NOT FOUND =====
    case DATA_NOT_FOUND = 'R_CMN_404_01';

    // ===== VALIDATION =====
    case VALIDATION_FAILED = 'R_CMN_422_01';

    // ===== SERVER =====
    case SERVER_ERROR = 'R_CMN_500_01';

    // ===== SERVICE =====
    case SERVICE_UNAVAILABLE = 'R_CMN_503_01';
    public function httpStatus(): int
    {
        $parts = explode('_', $this->value);

        return isset($parts[2]) ? (int) $parts[2] : 500;
    }
}
