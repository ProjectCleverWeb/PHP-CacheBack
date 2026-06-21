<?php

namespace CacheBack;

use CacheBack\Exception\E_InvalidConfig;

class Cache {
	/**
	 * @var CacheKey[]
	 */
	protected array $Keys;
	/**
	 * @var CacheLayer[]
	 */
	protected array $Layers;
	
	public function __construct() {
	
	}
	
	public function Fetch(string $Key): false|CacheResult {
		if (!$this->KeyExists($Key)) {
			return FALSE;
		}
		
		$OutdatedLayers = [];
		foreach ($this->Layers as $Layer) {
			$Data = $Layer->Fetch($Key);
			if (is_a($Data, CacheResult::class)) {
				$this->UpdateLayers($Key, $OutdatedLayers, $Data);
				
				return new $Data();
			}
			$OutdatedLayers[] = $Layer;
		}
		
		// Not found in any Layer, get from source and store in layers
		$Result = new CacheResult(($this->Keys[$Key]->callback)());
		$this->UpdateLayers($Key, $OutdatedLayers, $Result);
		
		return $Result;
	}
	
	/**
	 * @param string       $Key
	 * @param CacheLayer[] $Layers
	 * @param CacheResult  $Result
	 * @return void
	 */
	protected function UpdateLayers(
		string $Key,
		array $Layers,
		CacheResult $Result
	): void {
		if (empty($Layers)) {
			return;
		}
		foreach ($Layers as $Layer) {
			$Layer->Store($Key, $Result);
		}
	}
	
	public function KeyExists(string $key): bool {
		return isset($this->Keys[$key]);
	}
	
	public function RegisterKey(CacheKey $key): true {
		if ($this->KeyExists($key->id)) {
			throw new E_InvalidConfig('Key already exists');
		}
		
		$this->Keys[$key->id] = $key;
		
		return TRUE;
	}
	
	public function RegisterLayer(CacheLayer $layer): true {
		if ($this->LayerExists($layer->id)) {
			throw new E_InvalidConfig('Layer already exists');
		}
		$this->Layers[$layer->id] = $layer;
		usort($this->Layers, static fn($a, $b) => $a->priority <=> $b->priority);
		
		return TRUE;
	}
	
	public function LayerExists(string $id): bool {
		return isset($this->Layers[$id]);
	}
}
