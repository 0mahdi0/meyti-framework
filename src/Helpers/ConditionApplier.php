<?php

namespace App\Model;

use App\Exceptions\ConditionEvaluationException;
use App\Exceptions\OperationExecutionException;

class ConditionApplier
{
    private $variable;

    public function __construct($variable)
    {
        $this->variable = $variable;
    }

    public function applyConditions($conditions)
    {
        try {
            foreach ($conditions as $condition) {
                $condition = json_decode($condition, true);

                if ($this->evaluateStatement($condition['statement'])) {
                    $operation = $condition['operation'];

                    $this->performOperation($operation);
                }
            }

            return $this->variable;
        } catch (ConditionEvaluationException | OperationExecutionException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \Exception('Error applying conditions: ' . $e->getMessage());
        }
    }

    private function performOperation($operation)
    {
        try {
            if (is_callable($operation)) {
                $this->variable = call_user_func($operation, $this->variable);
            } elseif (is_string($operation) && strpos($operation, '@') !== false) {
                list($className, $methodName) = explode('@', $operation);
                if (class_exists($className)) {
                    $obj = new $className();
                    if (method_exists($obj, $methodName)) {
                        $this->variable = $obj->$methodName($this->variable);
                    } else {
                        throw new OperationExecutionException('Method not found in class');
                    }
                } else {
                    throw new OperationExecutionException('Class not found');
                }
            } elseif (class_exists($operation)) {
                $obj = new $operation();
                if (method_exists($obj, 'execute')) {
                    $this->variable = $obj->execute($this->variable);
                } else {
                    throw new OperationExecutionException('execute method not found in class');
                }
            } else {
                throw new OperationExecutionException('Invalid operation type');
            }
        } catch (OperationExecutionException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new OperationExecutionException('Error performing operation: ' . $e->getMessage());
        }
    }

    private function evaluateStatement($statement)
    {
        try {
            return eval('return ' . $statement . ';');
        } catch (\Exception $e) {
            throw new ConditionEvaluationException('Error evaluating statement: ' . $e->getMessage());
        }
    }
}
