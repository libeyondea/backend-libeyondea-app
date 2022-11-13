<?php

namespace App\Traits;

use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Validator as ValidatorFacade;

trait ModelValidatable
{
	public $validator;

	public function isValidFor(string $action = ''): bool
	{
		$this->validator = ValidatorFacade::make($this->attributes, $this->getRulesFor($action));

		return $this->validator()->passes();
	}

	public function isInvalidFor(string $action = ''): bool
	{
		return !$this->isValidFor($action);
	}

	public function validator(): Validator
	{
		return $this->validator;
	}

	private function getRulesFor(string $action): array
	{
		if (!method_exists($this, 'rules')) {
			return [];
		}

		$commonRules = $this->getRuleByAction('*');
		$actionRules = $this->getRuleByAction($action);

		return array_merge($commonRules, $actionRules);
	}

	private function getRuleByAction(string $action): array
	{
		if (!array_key_exists($action, $this->rules())) {
			return [];
		}

		return $this->rules()[$action];
	}
}
