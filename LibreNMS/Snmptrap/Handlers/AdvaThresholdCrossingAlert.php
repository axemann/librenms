<?php
/**
 * AdvaThresholdCrossingAlert.php
 *
 * -Description-
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Adva Threshold Exceeded Alarms.
 *
 * @package    LibreNMS
 * @link       http://librenms.org
 * @copyright  2018 KanREN, Inc
 * @author     Heath Barnhart <hbarnhart@kanren.net> & Neil Kahle <nkahle@kanren.net>
 */

namespace LibreNMS\Snmptrap\Handlers;

use App\Models\Device;
use LibreNMS\Interfaces\SnmptrapHandler;
use LibreNMS\Snmptrap\Trap;

class AdvaThresholdCrossingAlert implements SnmptrapHandler
{
    /**
     * Handle snmptrap.
     * Data is pre-parsed and delivered as a Trap.
     *
     * @param Device $device
     * @param Trap $trap
     * @return void
     */
    public function handle(Device $device, Trap $trap)
    {
        $device_array = $device->toArray();

        if ($trap->findOid("CM-PERFORMANCE-MIB::cmEthernetAccPortThreshold")) {
            $interval = $trap->getOidData($trap->findOid("CM-PERFORMANCE-MIB::cmEthernetAccPortThresholdInterval"));
            $ifName = $trap->getOidData($trap->findOid("IF-MIB::ifName"));
            $threshOid = $trap->getOidData($trap->findOid("CM-PERFORMANCE-MIB::cmEthernetAccPortThresholdVariable"));
            log_event("$ifName threshold exceeded for $interval. Threshold OID is $threshOid.", $device_array, 'trap', 2);
        }
    }
}
