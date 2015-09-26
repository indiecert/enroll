<?php

/**
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
require_once dirname(__DIR__).'/vendor/autoload.php';

use fkooman\IndieCert\Enroll\CertManager;
use fkooman\IndieCert\Enroll\EnrollService;
use fkooman\Tpl\Twig\TwigTemplateManager;
use fkooman\Ini\IniReader;

$iniReader = IniReader::fromFile(
    dirname(__DIR__).'/config/config.ini'
);

// CertManager
$caDir = $iniReader->v('CA', 'storageDir');
$caCrt = file_get_contents(sprintf('%s/ca.crt', $caDir));
$caKey = file_get_contents(sprintf('%s/ca.key', $caDir));

$certManager = new CertManager($caCrt, $caKey);

// TemplateManager
$templateManager = new TwigTemplateManager(
    array(
        dirname(__DIR__).'/views',
        dirname(__DIR__).'/config/views',
    ),
    $iniReader->v('templateCache', false, null)
);

$service = new EnrollService($certManager, $templateManager);
$service->run()->send();
