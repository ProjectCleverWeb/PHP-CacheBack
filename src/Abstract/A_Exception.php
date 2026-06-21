<?php

namespace CacheBack\Abstract;

use Exception;

abstract class A_Exception extends Exception {
	abstract protected string $typeName {
		get;
	}
	
	public function __construct($message) {
		parent::__construct(sprintf("[%s] %s", $this->typeName, $message));
	}
}
