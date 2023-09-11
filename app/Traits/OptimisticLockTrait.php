<?php

namespace App\Traits;

use DateTime;

trait OptimisticLockTrait
{
  public function checkExclusionary($mod_date)
  {
    $after = new DateTime($mod_date);
    if (strtotime($this->mod_date->format("Y-m-d H:i:s")) != strtotime($after->format("Y-m-d H:i:s"))) {
      return false;
    }
    return true;
  }
}
