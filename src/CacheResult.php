<?php

namespace CacheBack;

class CacheResult {
	
	public function __construct(
		private mixed $data
	) {
	
	}
	
	public function getValue() {
		return $this->data;
	}
	
	public function setValue(mixed $data) {
		$this->data = $data;
	}
	
}
