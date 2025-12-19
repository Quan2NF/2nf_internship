<?php

namespace App\Enums;

enum ResponseCode : string
{
    // Success
    case SUCCESS = 'R_CMN_200_01';

    // Client errors
    case INVALID_PARAMETER = 'R_CMN_400_01';
    case INVALID_TENANT = 'R_CMN_400_02';

    // Authentication / Authorization
    case EXPIRED_TOKEN = 'R_CMN_401_01';
    case INVALID_CREDENTIALS = 'R_CMN_401_02';
    case FORBIDDEN = 'R_CMN_403_01';
    case SERVICE_UNAVAILABLE_FOR_TENANT = 'R_CMN_403_02';

    // Not found
    case DATA_NOT_FOUND = 'R_CMN_404_01';
    case RESOURCE_FORBIDDEN = 'R_CMN_404_02';
    case DATA_DOES_NOT_EXIST = 'R_CMN_404_03';
    case TENANT_CONFIG_NOT_FOUND = 'R_CMN_404_04';
    case ALREADY_DELETED = 'R_CMN_404_05';

    // Validation errors
    case MALFORMED_DATA = 'R_CMN_422_01';
    case DATA_EXCEEDS_LIMIT = 'R_CMN_422_02';
    case DATA_BELOW_LIMIT = 'R_CMN_422_03';
    case DATA_EXCEEDS_CHAR_LIMIT = 'R_CMN_422_04';
    case DATA_BELOW_CHAR_LIMIT = 'R_CMN_422_05';
    case INVALID_TRANSMITTED_DATA = 'R_CMN_422_06';
    case EMPTY_DATA = 'R_CMN_422_07';
    case EMAIL_ALREADY_EXISTS = 'R_CMN_422_08';
    case FIELD_MUST_BE_LESS_THAN = 'R_CMN_422_09';
    case ACCOUNT_ALREADY_EXISTS = 'R_CMN_422_10';

    // Server errors
    case SERVER_ERROR = 'R_CMN_500_01';
    case INVALID_FILE_FORMAT = 'R_CMN_500_02';
    case FILE_TOO_LARGE = 'R_CMN_500_03';
    case FILE_DOWNLOAD_FAILED = 'R_CMN_500_04';
    case FILE_UPLOAD_FAILED = 'R_CMN_500_05';
    case FILE_DOWNLOAD_FAILED_AGAIN = 'R_CMN_500_06';

    // Service unavailable / maintenance
    case SERVICE_UNAVAILABLE = 'R_CMN_503_01';
    case CODE_MAINTENANCE = 'R_CMN_503_02';
}
