<?php

namespace Application\Service;

use App\Entity\EntitySeoRulesInterface;
use Application\Entity\Seo as SeoEntity;
use Application\SimpleObject\SeoData;
use Doctrine\ORM\EntityManager;

class Seo
{
    /**
     * Words suffixes
     *
     * @var array
     */
    protected $suffix = [];

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var array
     */
    protected $fields =[
        'title' => 'htmlTitle',
        'description' => 'metaDescription',
        'keywords' => 'metaKeywords',
    ];

    /**
     * UserController constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Get active seo rules
     *
     * @param string $uri
     *
     * @return SeoData[]
     */
    public function getSeoData($uri)
    {
        /** @var SeoData[] $seoData */
        $seoData = [];
        $title = $seoData[] = new SeoData('title');
        $metaKeywords = $seoData[] = new SeoData('keywords');
        $metaDescription = $seoData[] = new SeoData('description');

        $activeRules = $this->em->getRepository(SeoEntity::class)->findBy(
            ['status' => SeoEntity::STATUS_ACTIVE], ['sort' => 'ASC']
        );

        foreach ($this->suffix as $index => $value) {
            $title->addPlaceholder($index, $value);
            $metaKeywords->addPlaceholder($index, $value);
            $metaDescription->addPlaceholder($index, $value);
        }

        foreach ($activeRules as $rule) {
            if (!empty($rule->getUrl())) {
                if (@preg_match($rule->getUrl(), $uri)) {
                    if (!empty($rule->getTitle())) {
                        $title->applyPattern($rule->getTitle());
                    }
                    if (!empty($rule->getKeywords())) {
                        $metaKeywords->applyPattern($rule->getKeywords());
                    }
                    if (!empty($rule->getDescription())) {
                        $metaDescription->applyPattern($rule->getDescription());
                    }
                }
            }
        }

        return $seoData;
    }

    /**
     * Add suffixes for seo rules
     *
     * @param EntitySeoRulesInterface $entity
     */
    public function addSeoSuffix(EntitySeoRulesInterface $entity)
    {
        foreach ($this->fields as $key => $field) {
            $method = 'get' . $field;

            if (!empty($entity->{$method}())) {
                $this->suffix[$key] = $entity->{$method}();
            }
        }
    }
}
