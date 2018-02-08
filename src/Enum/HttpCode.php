<?php

namespace BootIq\CmsApiVendor\Enum;

final class HttpCode
{

    const HTTP_CODE_OK = 200;
    const HTTP_CODE_OK_EMPTY = 201;
    const HTTP_NOT_FOUND = 404;

    const SUCCESS_CODES = [
        self::HTTP_CODE_OK,
        self::HTTP_CODE_OK_EMPTY,
    ];
}
