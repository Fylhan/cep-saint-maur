<?php
namespace Filter;

interface Filterable {
	public function preFilter();
	public function postFilter();
}
