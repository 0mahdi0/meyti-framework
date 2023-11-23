<?php

namespace App\Exceptions;

use Exception;

class ConditionApplierException extends Exception
{
}

class ConditionEvaluationException extends ConditionApplierException
{
}

class OperationExecutionException extends ConditionApplierException
{
}
