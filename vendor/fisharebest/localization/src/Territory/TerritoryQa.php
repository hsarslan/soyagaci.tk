<?php namespace Fisharebest\Localization\Territory;

/**
 * Class AbstractTerritory - Representation of the territory QA - Qatar.
 *
 * @author    Greg Roach <fisharebest@gmail.com>
 * @copyright (c) 2015 Greg Roach
 * @license   GPLv3+
 */
class TerritoryQa extends AbstractTerritory implements TerritoryInterface {
	/** {@inheritdoc} */
	public function code() {
		return 'QA';
	}

	/** {@inheritdoc} */
	public function firstDay() {
		return 6;
	}

	/** {@inheritdoc} */
	public function weekendStart() {
		return 5;
	}

	/** {@inheritdoc} */
	public function weekendEnd() {
		return 6;
	}
}
