<?php
/* Copyright (C) 2026 ATM Consulting x Les Métiers du Bâtiment <developpeur@lesmetiersdubatiment.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

/**
 * Central compatibility information for UserNavHistory.
 *
 * @phpstan-type CompatibilityFeature array{
 *     label: string,
 *     description: string,
 *     min_dolibarr: string,
 *     core_available_from: string,
 *     module_available_from: string,
 *     min_php: string,
 *     compatibility_check: string,
 *     available: bool,
 *     reason: string
 * }
 */
class UserNavHistoryCompatibility
{
	const MIN_DOLIBARR_VERSION = '16.0.0';
	const RECOMMENDED_DOLIBARR_VERSION = '20.0.0';
	const MIN_PHP_VERSION = '7.0.0';
	const RECOMMENDED_PHP_VERSION = '8.0.0';

	/**
	 * Check the running Dolibarr version.
	 *
	 * @param string $version Minimum version.
	 * @return bool
	 */
	public static function isDolibarrVersionAtLeast($version)
	{
		return version_compare(DOL_VERSION, $version, '>=');
	}

	/**
	 * Check the running PHP version.
	 *
	 * @param string $version Minimum version.
	 * @return bool
	 */
	public static function isPhpVersionAtLeast($version)
	{
		return version_compare(PHP_VERSION, $version, '>=');
	}

	/**
	 * Return module features and their effective compatibility.
	 *
	 * @return array<string, CompatibilityFeature>
	 */
	public static function getCompatibilityFeatures()
	{
		$baseAvailable = self::isDolibarrVersionAtLeast(self::MIN_DOLIBARR_VERSION)
			&& self::isPhpVersionAtLeast(self::MIN_PHP_VERSION);

		return array(
			'navigation_hooks' => array(
				'label' => 'CompatibilityFeatureNavigationHooks',
				'description' => 'CompatibilityFeatureNavigationHooksDescription',
				'min_dolibarr' => self::MIN_DOLIBARR_VERSION,
				'core_available_from' => self::MIN_DOLIBARR_VERSION,
				'module_available_from' => self::MIN_DOLIBARR_VERSION,
				'min_php' => self::MIN_PHP_VERSION,
				'compatibility_check' => 'main/printMainArea and globalcard/doActions; verified again on Dolibarr 20 to 23',
				'available' => $baseAvailable,
				'reason' => $baseAvailable ? '' : 'CompatibilityReasonBaseVersion',
			),
			'navigation_history' => array(
				'label' => 'CompatibilityFeatureNavigationHistory',
				'description' => 'CompatibilityFeatureNavigationHistoryDescription',
				'min_dolibarr' => self::MIN_DOLIBARR_VERSION,
				'core_available_from' => self::MIN_DOLIBARR_VERSION,
				'module_available_from' => self::MIN_DOLIBARR_VERSION,
				'min_php' => self::MIN_PHP_VERSION,
				'compatibility_check' => "version_compare(DOL_VERSION, '16.0.0', '>=') && version_compare(PHP_VERSION, '7.0.0', '>=')",
				'available' => $baseAvailable,
				'reason' => $baseAvailable ? '' : 'CompatibilityReasonBaseVersion',
			),
			'native_hook_parent' => array(
				'label' => 'CompatibilityFeatureHookParent',
				'description' => 'CompatibilityFeatureHookParentDescription',
				'min_dolibarr' => self::MIN_DOLIBARR_VERSION,
				'core_available_from' => self::RECOMMENDED_DOLIBARR_VERSION,
				'module_available_from' => self::MIN_DOLIBARR_VERSION,
				'min_php' => self::MIN_PHP_VERSION,
				'compatibility_check' => 'Native from Dolibarr 20; bundled compatibility parent before Dolibarr 20',
				'available' => $baseAvailable,
				'reason' => $baseAvailable ? '' : 'CompatibilityReasonBaseVersion',
			),
			'native_global_helpers' => array(
				'label' => 'CompatibilityFeatureGlobalHelpers',
				'description' => 'CompatibilityFeatureGlobalHelpersDescription',
				'min_dolibarr' => self::MIN_DOLIBARR_VERSION,
				'core_available_from' => self::MIN_DOLIBARR_VERSION,
				'module_available_from' => self::MIN_DOLIBARR_VERSION,
				'min_php' => self::MIN_PHP_VERSION,
				'compatibility_check' => 'getDolGlobalInt(), getDolGlobalString() and isModEnabled()',
				'available' => $baseAvailable,
				'reason' => $baseAvailable ? '' : 'CompatibilityReasonBaseVersion',
			),
			'object_element_type' => array(
				'label' => 'CompatibilityFeatureElementType',
				'description' => 'CompatibilityFeatureElementTypeDescription',
				'min_dolibarr' => self::MIN_DOLIBARR_VERSION,
				'core_available_from' => self::RECOMMENDED_DOLIBARR_VERSION,
				'module_available_from' => self::MIN_DOLIBARR_VERSION,
				'min_php' => self::MIN_PHP_VERSION,
				'compatibility_check' => 'CommonObject::getElementType() from Dolibarr 20; bundled fallback before Dolibarr 20',
				'available' => $baseAvailable,
				'reason' => $baseAvailable ? '' : 'CompatibilityReasonBaseVersion',
			),
			'responsive_history' => array(
				'label' => 'CompatibilityFeatureResponsiveHistory',
				'description' => 'CompatibilityFeatureResponsiveHistoryDescription',
				'min_dolibarr' => self::MIN_DOLIBARR_VERSION,
				'core_available_from' => self::MIN_DOLIBARR_VERSION,
				'module_available_from' => self::MIN_DOLIBARR_VERSION,
				'min_php' => self::MIN_PHP_VERSION,
				'compatibility_check' => 'Server-side compatibility plus browser ResizeObserver fallback',
				'available' => $baseAvailable,
				'reason' => $baseAvailable ? '' : 'CompatibilityReasonBaseVersion',
			),
		);
	}

	/**
	 * Check one feature.
	 *
	 * @param string $featureCode Feature code.
	 * @return bool
	 */
	public static function isFeatureAvailable($featureCode)
	{
		$features = self::getCompatibilityFeatures();
		return isset($features[$featureCode]) && !empty($features[$featureCode]['available']);
	}

	/**
	 * Return unavailable features.
	 *
	 * @return array<string, CompatibilityFeature>
	 */
	public static function getUnavailableFeatures()
	{
		$unavailable = array();
		foreach (self::getCompatibilityFeatures() as $code => $feature) {
			if (empty($feature['available'])) {
				$unavailable[$code] = $feature;
			}
		}

		return $unavailable;
	}
}
