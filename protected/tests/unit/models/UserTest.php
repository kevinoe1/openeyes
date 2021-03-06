<?php

/**
 * OpenEyes.
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2013
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @link http://www.openeyes.org.uk
 *
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2011-2013, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/agpl-3.0.html The GNU Affero General Public License V3.0
 */
class UserTest extends CDbTestCase
{
    public $fixtures = array(
        'firms' => 'Firm',
        'FirmUserAssignment',
        'Service',
        'ServiceSubspecialtyAssignment',
        'users' => 'User',
        'UserFirmRights',
        'UserServiceRights',
    );

    public function dataProvider_Search()
    {
        return array(
            array(array('username' => 'Joe'), 1, array('user1')),
            array(array('username' => 'Jane'), 1, array('user2')),
            array(array('last_name' => 'bloggs'), 2, array('user1', 'user2')), /* case insensitivity test - needs _ci column collation */
            array(array('username' => 'no-one'), 0, array()),
        );
    }

    /**
     * @dataProvider dataProvider_Search
     */
    public function testSearch_WithValidTerms_ReturnsExpectedResults($searchTerms, $numResults, $expectedKeys)
    {
        $user = new User();
        $searchTerms['global_firm_rights'] = null; // ignore what setting global_firm_rights has
        $user->setAttributes($searchTerms, true);
        $results = $user->search();
        $data = $results->getData();

        $expectedResults = array();
        if (!empty($expectedKeys)) {
            foreach ($expectedKeys as $key) {
                $expectedResults[] = $this->users($key);
            }
        }

        $this->assertEquals($numResults, $results->getItemCount());
        $this->assertEquals($expectedResults, $data);
    }

    public function testGetAvailableFirms_GlobalRights()
    {
        $firms = $this->users('user1')->getAvailableFirms();
        $this->assertCount(count($this->firms), $firms);
    }

    public function testGetAvailableFirms_FirmUserAssignment()
    {
        $firms = $this->users('user2')->getAvailableFirms();
        $this->assertCount(1, $firms);
        $this->assertEquals('Collin Firm', $firms[0]->name);
    }

    public function testGetAvailableFirms_UserFirmRights()
    {
        $firms = $this->users('user3')->getAvailableFirms();
        $this->assertCount(1, $firms);
        $this->assertEquals('Allan Firm', $firms[0]->name);
    }

    public function testGetAvailableFirms_UserServiceRights()
    {
        $firms = $this->users('admin')->getAvailableFirms();
        $this->assertCount(1, $firms);
        $this->assertEquals('Aylward Firm', $firms[0]->name);
    }
}
