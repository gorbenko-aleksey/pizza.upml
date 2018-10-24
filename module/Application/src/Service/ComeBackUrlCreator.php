<?php

namespace Application\Service;

class ComeBackUrlCreator
{
    /**
     * Parameter name
     */
    const PARAM_NAME = 'return_url';

    /**
     * Get url for redirect on previous page
     *
     * @param array $params
     *
     * @return string
     */
    public function getComeBackUrl(array $params)
    {
        $redirectUrl = '';

        if (!empty($params[self::PARAM_NAME])) {
            $redirectUrl = urldecode($params[self::PARAM_NAME]);
        }

        $position = stripos($redirectUrl, self::PARAM_NAME);

        if (!$redirectUrl) {
            return $redirectUrl;
        }

        if ($position !== false) {
            $borderPosition = $position + strlen(self::PARAM_NAME) + 1;
            $redirectUrl = mb_substr($redirectUrl, 0, $borderPosition) .
                urlencode(mb_substr($redirectUrl, $borderPosition, strlen($redirectUrl)));
        }

        return $redirectUrl;
    }
}