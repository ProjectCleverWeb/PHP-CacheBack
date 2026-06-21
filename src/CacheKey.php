<?php

namespace CacheBack;

use Closure;

class CacheKey {
	
	public readonly array|object|string $callback;
	
	public function __construct(
		public readonly string $id,
		callable $callback,
	) {
		$this->callback = $callback;
	}
}
