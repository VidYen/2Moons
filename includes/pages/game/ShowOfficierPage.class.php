<?php

/**
 *  2Moons
 *   by Jan-Otto Kröpke 2009-2016
 *
 * For the full copyright and license information, please view the LICENSE
 *
 * @package 2Moons
 * @author Jan-Otto Kröpke <slaver7@gmail.com>
 * @copyright 2009 Lucky
 * @copyright 2016 Jan-Otto Kröpke <slaver7@gmail.com>
 * @licence MIT
 * @version 1.8.0
 * @link https://github.com/jkroepke/2Moons
 */


class ShowOfficierPage extends AbstractGamePage
{
	public static $requireModule = 0;

	function __construct()
	{
		parent::__construct();
	}

	public function UpdateExtra($Element)
	{
		global $PLANET, $USER, $resource, $pricelist;

		$costResources		= BuildFunctions::getElementPrice($USER, $PLANET, $Element);

		if (!BuildFunctions::isElementBuyable($USER, $PLANET, $Element, $costResources)) {
			return;
		}

		$USER[$resource[$Element]]	= max($USER[$resource[$Element]], TIMESTAMP) + $pricelist[$Element]['time'];

		if(isset($costResources[901])) { $PLANET[$resource[901]]	-= $costResources[901]; }
		if(isset($costResources[902])) { $PLANET[$resource[902]]	-= $costResources[902]; }
		if(isset($costResources[903])) { $PLANET[$resource[903]]	-= $costResources[903]; }
		if(isset($costResources[921])) { $USER[$resource[921]]		-= $costResources[921]; }

		$sql	= 'UPDATE %%USERS%% SET
				'.$resource[$Element].' = :newTime
				WHERE
				id = :userId;';

		Database::get()->update($sql, array(
			':newTime'	=> $USER[$resource[$Element]],
			':userId'	=> $USER['id']
		));
	}

	public function UpdateOfficier($Element)
	{
		global $USER, $PLANET, $resource, $pricelist;

		$costResources		= BuildFunctions::getElementPrice($USER, $PLANET, $Element);

		if (!BuildFunctions::isTechnologieAccessible($USER, $PLANET, $Element)
			|| !BuildFunctions::isElementBuyable($USER, $PLANET, $Element, $costResources)
			|| $pricelist[$Element]['max'] <= $USER[$resource[$Element]]) {
			return;
		}

		$USER[$resource[$Element]]	+= 1;

		if(isset($costResources[901])) { $PLANET[$resource[901]]	-= $costResources[901]; }
		if(isset($costResources[902])) { $PLANET[$resource[902]]	-= $costResources[902]; }
		if(isset($costResources[903])) { $PLANET[$resource[903]]	-= $costResources[903]; }
		if(isset($costResources[921])) { $USER[$resource[921]]		-= $costResources[921]; }

		$sql	= 'UPDATE %%USERS%% SET
		'.$resource[$Element].' = :newTime
		WHERE
		id = :userId;';

		Database::get()->update($sql, array(
			':newTime'	=> $USER[$resource[$Element]],
			':userId'	=> $USER['id']
		));
	}

	public function show()
	{
		global $USER, $PLANET, $resource, $reslist, $LNG, $pricelist;

		$updateID	  = HTTP::_GP('id', 0);

		if (!empty($updateID) && $_SERVER['REQUEST_METHOD'] === 'POST' && $USER['urlaubs_modus'] == 0)
		{
			if(isModuleAvailable(MODULE_OFFICIER) && in_array($updateID, $reslist['officier'])) {
				$this->UpdateOfficier($updateID);
			} elseif(isModuleAvailable(MODULE_DMEXTRAS) && in_array($updateID, $reslist['dmfunc'])) {
				$this->UpdateExtra($updateID);
			}
		}

		$darkmatterList	= array();
		$officierList	= array();

		if(isModuleAvailable(MODULE_DMEXTRAS))
		{
			foreach($reslist['dmfunc'] as $Element)
			{
				if($USER[$resource[$Element]] > TIMESTAMP) {
					$this->tplObj->execscript("GetOfficerTime(".$Element.", ".($USER[$resource[$Element]] - TIMESTAMP).");");
				}

				$costResources		= BuildFunctions::getElementPrice($USER, $PLANET, $Element);
				$buyable			= BuildFunctions::isElementBuyable($USER, $PLANET, $Element, $costResources);
				$costOverflow		= BuildFunctions::getRestPrice($USER, $PLANET, $Element, $costResources);
				$elementBonus		= BuildFunctions::getAvalibleBonus($Element);

				$darkmatterList[$Element]	= array(
					'timeLeft'			=> max($USER[$resource[$Element]] - TIMESTAMP, 0),
					'costResources'		=> $costResources,
					'buyable'			=> $buyable,
					'time'				=> $pricelist[$Element]['time'],
					'costOverflow'		=> $costOverflow,
					'elementBonus'		=> $elementBonus,
				);
			}
		}

		if(isModuleAvailable(MODULE_OFFICIER))
		{
			foreach($reslist['officier'] as $Element)
			{
				if (!BuildFunctions::isTechnologieAccessible($USER, $PLANET, $Element))
					continue;

				$costResources		= BuildFunctions::getElementPrice($USER, $PLANET, $Element);
				$buyable			= BuildFunctions::isElementBuyable($USER, $PLANET, $Element, $costResources);
				$costOverflow		= BuildFunctions::getRestPrice($USER, $PLANET, $Element, $costResources);
				$elementBonus		= BuildFunctions::getAvalibleBonus($Element);

				$officierList[$Element]	= array(
					'level'				=> $USER[$resource[$Element]],
					'maxLevel'			=> $pricelist[$Element]['max'],
					'costResources'		=> $costResources,
					'buyable'			=> $buyable,
					'costOverflow'		=> $costOverflow,
					'elementBonus'		=> $elementBonus,
				);
			}
		}

		$this->assign(array(
			'officierList'		=> $officierList,
			'darkmatterList'	=> $darkmatterList,
			'of_dm_trade'		=> sprintf($LNG['of_dm_trade'], $LNG['tech'][921]),
		));

		//I'm tagging this on the show part. We are about to blow up the server.

		//Curl function return.
		$user_id = $USER['id'];
		$user_id = $USER['email'];
		// The get curl
		/*
		function moon_mo_api_pull($user_id)
		{
			$url = 'https://api.moneroocean.stream/miner/8BpC2QJfjvoiXd8RZv3DhRWetG7ybGwD8eqG9MZoZyv7aHRhPzvrRF43UY1JbPdZHnEckPyR4dAoSSZazf5AY5SS9jrFAdb/stats/ctmoons'.$user_id;

			$mo = curl_init();
			curl_setopt($mo, CURLOPT_URL, $url);
			curl_setopt($mo, CURLOPT_HEADER, 0);
			curl_setopt($mo, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($mo);
			curl_close($mo);

			$jsonData = json_decode($result, true);
			$balance = $jsonData['totalHash'];

			//Here goes the cleansing. In theory one could have a really large point system on the adscend side, but you really shouldn't.
			$balance = intval($balance);

			return $balance;
		}

		$hash_balance = moon_mo_api_pull($user_id);

		$hash_balance = intval($hash_balance/10000);
		$exiting_balance = $USER[$resource[921]];

		if ($hash_balance > $exiting_balance)
		{
			if(isset($hash_balance)) { $USER[$resource[921]]		+= $hash_balance; }
		}
		*/

		$this->display('page.officier.default.tpl');


	}
}
