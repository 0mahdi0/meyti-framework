<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Model\ConditionApplier;
use App\Exceptions\ConditionEvaluationException;
use App\Exceptions\OperationExecutionException;

class ConditionApplierTest extends TestCase
{
    public function testApplyConditions()
    {
        $myVariable = 5;

        $jsonConditions = '[
            {"statement": "$myVariable > 0", "operation": "subtractFunction"},
            {"statement": "$myVariable == 0", "operation": "MyClass@executeMethod"}
        ]';

        $conditions = json_decode($jsonConditions, true);

        $conditionApplier = new ConditionApplier($myVariable);

        try {
            $result = $conditionApplier->applyConditions($conditions);
            $this->assertEquals(6, $result); // Expected result after applying conditions
        } catch (ConditionEvaluationException | OperationExecutionException $e) {
            $this->fail('Exception caught: ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->fail('Unexpected exception caught: ' . $e->getMessage());
        }
    }
}
