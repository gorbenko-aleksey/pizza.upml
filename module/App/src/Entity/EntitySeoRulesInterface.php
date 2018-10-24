<?php

namespace App\Entity;

interface EntitySeoRulesInterface
{
    /**
     * @return string
     */
    public function getHtmlTitle();

    /**
     * @return string
     */
    public function getMetaDescription();

    /**
     * @return string
     */
    public function getMetaKeywords();
}
