<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest;

use ArchivedObjectException;
use CMDBObject;
use CMDBSource;
use Combodo\iTop\Service\Events\EventData;
use Combodo\iTop\Service\Events\EventService;
use Config;
use Contact;
use DBObject;
use DBObjectSet;
use DBSearch;
use Exception;
use Farm;
use FunctionalCI;
use Hypervisor;
use lnkContactToFunctionalCI;
use lnkContactToTicket;
use lnkFunctionalCIToTicket;
use MetaModel;
use Person;
use RunTimeEnvironment;
use Server;
use SetupUtils;
use TagSetFieldData;
use Ticket;
use URP_UserProfile;
use utils;
use VirtualHost;
use VirtualMachine;
use XMLDataLoader;


/**
 * Class ItopCustomDatamodelTestCase
 *
 * Helper class to extend for tests needing a custom DataModel access to iTop's metamodel
 *
 * **⚠ Warning** Each class extending this one needs to add the following annotations :
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 *
 * @since 2.7.9 3.0.4 3.1.0
 */
abstract class ItopCustomDatamodelTestCase extends ItopDataTestCase
{
	/**
	 * @return string Abs path to the XML delta to use for the tests of that class
	 */
	abstract public function GetDatamodelDeltaAbsPath(): string;

	/**
	 * @inheritDoc
	 */
	protected function tearDown(): void
	{
		// Clean temp "php-unit-tests"  environment
		$sTestEnv = $this->GetTestEnvironment();
		// TODO: Clean test env
		// - Configuration file
		// TODO: Find proper way
		$sConfFile = utils::GetConfigFilePath($sTestEnv);
		$sConfFolder = dirname($sConfFile);
		chmod($sConfFile, 777);
		SetupUtils::tidydir($sConfFolder);
		// - Datamodel delta file
		$sDeltaFile = APPROOT.'data/'.$sTestEnv.'.delta.xml';
		unlink($sDeltaFile);
		// - Cache directory
		SetupUtils::rrmdir(utils::GetCachePath());
		// - Compiled directory
		SetupUtils::rrmdir(APPROOT.'env-'.$sTestEnv);
		// - Drop database
		// TODO: How?

		parent::tearDown();
	}

	/**
	 * @inheritDoc
	 */
	protected function LoadRequiredFiles(): void
	{
		$this->RequireOnceItopFile('setup/setuputils.class.inc.php');
		$this->RequireOnceItopFile('setup/runtimeenv.class.inc.php');
	}

	/**
	 * @return string Environment used as a base (conf. file, modules, DB, ...) to prepare the test environment
	 */
	protected function GetSourceEnvironment(): string
	{
		return 'production';
	}

	/**
	 * @inheritDoc
	 */
	protected function GetTestEnvironment(): string
	{
		return 'php-unit-tests';
	}

	/**
	 * @inheritDoc
	 */
	protected function PrepareEnvironment(): void
	{
		$sSourceEnv = $this->GetSourceEnvironment();
		$sTestEnv = $this->GetTestEnvironment();

		// All the following is greatly inspired by the toolkit's sandbox script
		// - Prepare config file
		$oConfig = new Config(utils::GetConfigFilePath($sSourceEnv));
		if ($oConfig->Get('source_dir') === '')
		{
			throw new Exception('Missing entry source_dir from the config file');
		}

		$oTestConfig = clone($oConfig);
		$oTestConfig->ChangeModulesPath($sSourceEnv, $sTestEnv);

		// - Prepare delta file
		SetupUtils::copydir($this->GetDatamodelDeltaAbsPath(), APPROOT.'data/'.$sTestEnv.'.delta.xml');

		// - Compile env. based on the existing 'production' env.
		$oEnvironment = new RunTimeEnvironment($sTestEnv);
		$oEnvironment->WriteConfigFileSafe($oTestConfig);
		$oEnvironment->CompileFrom($sSourceEnv, false);

		// TODO: Create tmp DB

		parent::PrepareEnvironment();
	}
}
