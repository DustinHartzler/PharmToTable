<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 *
 * Modified by woocommerce on 12-April-2023 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Automattic\WooCommerce\Bookings\Vendor\Google\Service\Calendar;

class ConferenceParameters extends \Automattic\WooCommerce\Bookings\Vendor\Google\Model
{
  protected $addOnParametersType = ConferenceParametersAddOnParameters::class;
  protected $addOnParametersDataType = '';

  /**
   * @param ConferenceParametersAddOnParameters
   */
  public function setAddOnParameters(ConferenceParametersAddOnParameters $addOnParameters)
  {
    $this->addOnParameters = $addOnParameters;
  }
  /**
   * @return ConferenceParametersAddOnParameters
   */
  public function getAddOnParameters()
  {
    return $this->addOnParameters;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ConferenceParameters::class, 'Google_Service_Calendar_ConferenceParameters');
