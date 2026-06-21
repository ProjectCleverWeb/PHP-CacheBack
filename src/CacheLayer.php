<?php

namespace CacheBack;

use CacheBack\Abstract\A_LayerType;

class CacheLayer {
	public function __construct(
		public readonly string $id,
		public readonly A_LayerType $type,
		public readonly int $priority,
	) {}
	
	public function Fetch(string $key): CacheResult|EmptyCacheResult {
		return rand(0, 1) ? new CacheResult(['hello' => 'world']) : new EmptyCacheResult();
	}
	
	public function Store(string $key, CacheResult $data): void {
	
	}
}
