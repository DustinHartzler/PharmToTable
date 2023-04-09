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
 * Modified by woocommerce on 27-March-2023 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Automattic\WooCommerce\Bookings\Vendor\Google\Service\Calendar;

class ConferenceSolution extends \Automattic\WooCommerce\Bookings\Vendor\Google\Model
{
  /**
   * @var string
   */
  public $iconUri;
  protected $keyType = ConferenceSolutionKey::class;
  protected $keyDataType = '';
  public $key;
  /**
   * @var string
   */
  public $name;

  /**
   * @param string
   */
  public function setIconUri($iconUri)
  {
    $this->iconUri = $iconUri;
  }
  /**
   * @return string
   */
  public function getIconUri()
  {
    return $this->iconUri;
  }
  /**
   * @param ConferenceSolutionKey
   */
  public function setKey(ConferenceSolutionKey $key)
  {
    $this->key = $key;
  }
  /**
   * @return ConferenceSolutionKey
   */
  public function getKey()
  {
    return $this->key;
  }
  /**
   * @param string
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ConferenceSolution::class, 'Google_Service_Calendar_ConferenceSolution');