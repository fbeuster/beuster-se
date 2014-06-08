<?php
	$a['filename'] = 'portfolio.php';

	# request portfolio sets
	$fields = array('ID');
	$conds = array('Typ = ? AND ParentId != ?', 'ii', array(3, 1));
	$options = 'ORDER BY Cat ASC';
	$db = Database::getDB();
	$res = $db->select('newscat', $fields, $conds, $options);

	# fill portfolio sets
	$portSets = array();
	foreach ($res as $set) {
		$portSets[] = new PortfolioSet($set['ID']);
	}

	$a['data']['portSets'] = $portSets;
	$a['data']['ret'] = '';
?>