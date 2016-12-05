<?php
namespace Lib;
/**
 * 分页
 */
Class Page {

	public $rows;
	public $totalrows;
	public $nowpage;
	public $totalpages;
	public $firstrow;

	public function __construct($totalrows, $rows = 15) {
		// 总行数
		$this->totalrows = intval($totalrows);
		// 每页行数
		$this->rows = $rows;
		// 总页数
		$this->totalpages = ceil($this->totalrows / $this->rows);
		// 当前页
		$this->nowpage = I('post.page', 1, 'int');
		if ($this->nowpage < 1) {
			$this->nowpage = 1;
		} elseif (empty($this->totalpages)) {
			$this->nowpage = 1;
			$this->totalpages = 1;
		} elseif ($this->nowpage > $this->totalpages) {
			$this->nowpage = $this->totalpages;
		}
		// 当前页第一行
		$this->firstrow = $this->rows * ($this->nowpage - 1);
	}
}
?>