<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swag\PayPal\Util;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;

class LocaleCodeProvider
{
    private EntityRepositoryInterface $languageRepository;

    public function __construct(EntityRepositoryInterface $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    public function getLocaleCodeFromContext(Context $context): string
    {
        $languageId = $context->getLanguageId();
        $criteria = new Criteria([$languageId]);
        $criteria->addAssociation('locale');
        $criteria->setLimit(1);
        $language = $this->languageRepository->search($criteria, $context)->first();
        if ($language === null) {
            return 'en-GB';
        }

        $locale = $language->getLocale();
        if (!$locale) {
            return 'en-GB';
        }

        return $locale->getCode();
    }
}
