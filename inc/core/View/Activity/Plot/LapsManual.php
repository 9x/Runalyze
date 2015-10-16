<?php
/**
 * This file contains class::LapsManual
 * @package Runalyze\View\Activity\Plot
 */

namespace Runalyze\View\Activity\Plot;

use Runalyze\Activity\Distance;
use Runalyze\Activity\Duration;
use Runalyze\Activity\Pace as PaceObject;
use Runalyze\Model\Activity\Splits;
use Runalyze\Util\StringReader;
use Runalyze\View\Activity;

/**
 * Plot for: manual laps
 * 
 * @author Hannes Christiansen
 * @package Runalyze\View\Activity\Plot
 */
class LapsManual extends Laps {
	/**
	 * Set key and title for this plot
	 */
	protected function setKey() {
		$this->key = 'laps_manual';
		$this->title = __('Manual Laps');
	}

	/**
	 * Load data
	 * @param \Runalyze\View\Activity\Context $context
	 */
	protected function loadData(Activity\Context $context) {
		$Splits = $context->activity()->splits();

		if ($Splits->isEmpty() || $Splits->totalDistance() <= 0) {
			$this->Plot->raiseError( __('There are no manual laps.') );
			return;
		}

		$this->readDataFromSplits($Splits, !$Splits->hasActiveLaps(2));

		$num = count($this->Data);

		$Reader = new StringReader($context->activity()->comment());

		$this->demandedPace = $Reader->findDemandedPace();
		$this->achievedPace = array_sum($this->Data) / $num;

		$this->manipulateData($num);
	}

	/**
	 * @param \Runalyze\Model\Activity\Splits\Object $splits
	 * @param boolean $showInactive
	 */
	protected function readDataFromSplits(Splits\Object $splits, $showInactive) {
		foreach ($splits->asArray() as $split) {
			if (($showInactive || $split->isActive()) && $split->distance() > 0) {
				$this->Labels[] = round((new Distance($split->distance()))->valueInPreferredUnit(), 2);
				$this->Data[] = $split->time() / $split->distance();
			}
		}
	}

	/**
	 * @param int $num
	 */
	protected function manipulateData($num) {
		$Pace = new PaceObject(0, 1);
		$Pace->setUnit($this->PaceUnit);

		foreach ($this->Data as $key => $val) {
			if ($num > 35) {
				$this->Labels[$key] = array($key, round($this->Labels[$key], 1));
			} elseif ($num >= 20) {
				$this->Labels[$key] = array($key, number_format($this->Labels[$key], 1, '.', ''));
			} elseif ($num > 10) {
				$this->Labels[$key] = array($key, $this->Labels[$key].'k');
			} else {
				$this->Labels[$key] = array($key, $this->Labels[$key].' km');
			}

			$Pace->setTime($val);

			if ($this->PaceUnit->isTimeFormat()) {
				$this->Data[$key] = 1000 * round($Pace->secondsPerKm() * $this->PaceUnit->factorForUnit());
			} else {
				$this->Data[$key] = (float)str_replace(',', '.', $Pace->value());
			}
		}

		$this->Plot->Data[] = array('label' => $this->title, 'data' => $this->Data);
	}
}
