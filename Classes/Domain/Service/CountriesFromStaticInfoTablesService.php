<?php
declare(strict_types=1);
namespace In2code\Powermail\Domain\Service;

use SJBR\StaticInfoTables\Domain\Repository\CountryRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\Exception;
use TYPO3\CMS\Extbase\Reflection\Exception\PropertyNotAccessibleException;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * Class CountriesFromStaticInfoTablesService
 */
class CountriesFromStaticInfoTablesService
{

    /**
     * Build array with countries
     *
     * @param string $key
     * @param string $value
     * @param string $sortbyField
     * @param string $sorting
     * @return array
     * @throws Exception
     * @throws PropertyNotAccessibleException
     */
    public function getCountries(
        $key = 'isoCodeA3',
        $value = 'officialNameLocal',
        $sortbyField = 'isoCodeA3',
        $sorting = 'asc'
    ): array {
        $countryRepository = GeneralUtility::makeInstance(CountryRepository::class);
        $countries = $countryRepository->findAllOrderedBy($sortbyField, $sorting);
        $countriesArray = [];
        foreach ($countries as $country) {
            /** @var $country \SJBR\StaticInfoTables\Domain\Model\Country */
            $countriesArray[ObjectAccess::getProperty($country, $key)] = ObjectAccess::getProperty($country, $value);
        }
        return $countriesArray;
    }
}
