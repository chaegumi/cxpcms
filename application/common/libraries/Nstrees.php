<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
  Nested Set Tree Library
  
  Author:  Rolf Brugger, edutech
  Version: 0.02, 5. April 2005
  URL:     http://www.edutech.ch/contribution/nstrees
  
  DB-Model by Joe Celko (http://www.celko.com/)
  
  References:
    http://www.sitepoint.com/article/1105/2
    http://searchdatabase.techtarget.com/tip/1,289483,sid13_gci537290,00.html
    http://dbforums.com/arch/57/2002/6/400142



  Datastructures:
  ---------------
  
  Handle:
    key: 'table':    name of the table that contains the tree structure
	key: 'lvalname': name of the attribute (field) that contains the left value
	key: 'rvalname': name of the attribute (field) that contains the right value
	
  Node:
    key 'l': left value
	key 'r': right value
	
	
  Orientation
  -----------
  
      n0
	 / | \
   n1  N  n3
     /   \
   n4     n5
   
  directions from the perspective of the node N:
    n0: up / ancestor
	n1: previous (sibling)
	n3: next (sibling)
	n4: first (child)
	n5: last (child)
     
*/
/**
 *
 * @author		Chaegumi
 * @copyright	Copyright (c) 2013 chaegumi
 * @email		chaegumi@qq.com
 * @filesource
 */
class Nstrees{

	private $db;
	
	private $thandle;
	
	function __construct($options = array()){
		$CI = &get_instance();
		$this->db = $CI->db;
		$this->thandle = $options;
	}


	/* ******************************************************************* */
	/* Tree Constructors */
	/* ******************************************************************* */

	function nstNewRoot ($othercols)
	/* creates a new root record and returns the node 'l'=1, 'r'=2. */
	{
	  $newnode['l'] = 1;
	  $newnode['r'] = 2;
	  $newnode['root_id'] = 0;
	  $this->db->query('LOCK TABLE ' . $this->thandle['table'] . ' WRITE');
	  $this->_insertNew ($newnode, $othercols);
	  // 更新rootid
	  $new_root_id = $this->db->insert_id();
	  $this->db->where('id', $new_root_id);
	  $this->db->update($this->thandle['table'], array('root_id' => $new_root_id));
	  $this->db->query('UNLOCK TABLES');
	  $newnode['root_id'] = $new_root_id;
	  return $newnode;
	}

	function nstNewFirstChild ($node, $othercols)
	/* creates a new first child of 'node'. */
	{
	  $newnode['l'] = $node['l']+1;
	  $newnode['r'] = $node['l']+2;
	  $newnode['root_id'] = $node['root_id'];
	  $this->db->query('LOCK TABLE ' . $this->thandle['table'] . ' WRITE');
	  $this->_shiftRLValues($newnode['l'], 2, $newnode['root_id']);
	  $this->_insertNew ($newnode, $othercols);
	  $this->db->query('UNLOCK TABLES');
	  return $newnode;
	}

	function nstNewLastChild ($node, $othercols)
	/* creates a new last child of 'node'. */
	{
	  $newnode['l'] = $node['r'];
	  $newnode['r'] = $node['r']+1;
	  $newnode['root_id'] = $node['root_id'];
	  $this->db->query('LOCK TABLE ' . $this->thandle['table'] . ' WRITE');
	  $this->_shiftRLValues($newnode['l'], 2, $newnode['root_id']);
	  $this->_insertNew ($newnode, $othercols);
	  $this->db->query('UNLOCK TABLES');
	  return $newnode;
	}

	function nstNewPrevSibling ($node, $othercols)
	{
	  $newnode['l'] = $node['l'];
	  $newnode['r'] = $node['l']+1;
	  $newnode['root_id'] = $node['root_id'];
	  $this->db->query('LOCK TABLE ' . $this->thandle['table'] . ' WRITE');
	  $this->_shiftRLValues($newnode['l'], 2, $newnode['root_id']);
	  $this->_insertNew ($newnode, $othercols);
	  $this->db->query('UNLOCK TABLES');
	  return $newnode;
	}

	function nstNewNextSibling ($node, $othercols)
	{
	  $newnode['l'] = $node['r']+1;
	  $newnode['r'] = $node['r']+2;
	  $newnode['root_id'] = $node['root_id'];
	  $this->db->query('LOCK TABLE ' . $this->thandle['table'] . ' WRITE');
	  $this->_shiftRLValues($newnode['l'], 2, $newnode['root_id']);
	  $this->_insertNew ($newnode, $othercols);
	  $this->db->query('UNLOCK TABLES');
	  return $newnode;
	}


	/* *** internal routines *** */

	function _shiftRLValues ($first, $delta, $root_id)
	/* adds '$delta' to all L and R values that are >= '$first'. '$delta' can also be negative. */
	{ //print("SHIFT: add $delta to gr-eq than $first <br/>");
	  $this->db->query("UPDATE ".$this->thandle['table']." SET ".$this->thandle['lvalname']."=".$this->thandle['lvalname']."+$delta WHERE ".$this->thandle['lvalname'].">=$first AND root_id=" . $root_id);
	  $this->db->query("UPDATE ".$this->thandle['table']." SET ".$this->thandle['rvalname']."=".$this->thandle['rvalname']."+$delta WHERE ".$this->thandle['rvalname'].">=$first AND root_id=" . $root_id);
	}
	function _shiftRLRange ($first, $last, $delta, $root_id)
	/* adds '$delta' to all L and R values that are >= '$first' and <= '$last'. '$delta' can also be negative. 
	   returns the shifted first/last values as node array.
	 */
	{
	  $this->db->query("UPDATE ".$this->thandle['table']." SET ".$this->thandle['lvalname']."=".$this->thandle['lvalname']."+$delta WHERE ".$this->thandle['lvalname'].">=$first AND ".$this->thandle['lvalname']."<=$last AND root_id=" . $root_id);
	  $this->db->query("UPDATE ".$this->thandle['table']." SET ".$this->thandle['rvalname']."=".$this->thandle['rvalname']."+$delta WHERE ".$this->thandle['rvalname'].">=$first AND ".$this->thandle['rvalname']."<=$last AND root_id=" . $root_id);
	  return array('l'=>$first+$delta, 'r'=>$last+$delta);
	}

	function _insertNew ($node, $othercols)
	/* creates a new root record and returns the node 'l'=1, 'r'=2. */
	{

		if(is_array($othercols)){
			$newdata = array(
				$this->thandle['lvalname'] => $node['l'],
				$this->thandle['rvalname'] => $node['r'],
				'root_id' => $node['root_id']
			);
			$newdata = array_merge($newdata, $othercols);
			$this->db->insert($this->thandle['table'], $newdata);
		}else{
		  if (strlen($othercols)>0){$othercols .= ",";}
		  $res = $this->db->query("INSERT INTO ".$this->thandle['table']." SET $othercols"
				 .$this->thandle['lvalname']."=".$node['l'].", ".$this->thandle['rvalname']."=".$node['r'].", root_id=" . $node['root_id']);		
		}
		
		
	  // if (!$res) {$this->_prtError();}
	}


	/* ******************************************************************* */
	/* Tree Reorganization */
	/* ******************************************************************* */

	/* all nstMove... functions return the new position of the moved subtree. */
	function nstMoveToNextSibling ($src, $dst)
	/* moves the node '$src' and all its children (subtree) that it is the next sibling of '$dst'. */
	{
		
		// var_dump($src, $dst);
	  return $this->_moveSubtree ($src, $dst['r']+1, $dst['parent_id']);
	}

	function nstMoveToPrevSibling ($src, $dst)
	/* moves the node '$src' and all its children (subtree) that it is the prev sibling of '$dst'. */
	{
	  return $this->_moveSubtree ($src, $dst['l']);
	}

	function nstMoveToFirstChild ($src, $dst)
	/* moves the node '$src' and all its children (subtree) that it is the first child of '$dst'. */
	{
	  return $this->_moveSubtree ($src, $dst['l']+1);
	}

	function nstMoveToLastChild ($src, $dst)
	/* moves the node '$src' and all its children (subtree) that it is the last child of '$dst'. */
	{
		// 更新parent_id
		// var_dump($src, $dst);
		
	  return $this->_moveSubtree ($src, $dst['r'], $dst['parent_id']);
	}

	function _moveSubtree ($src, $to, $parent='0')
	/* '$src' is the node/subtree, '$to' is its destination l-value */
	{ 
		$this->db->query('LOCK TABLE ' . $this->thandle['table'] . ' WRITE');
		if($parent != '0'){
			// 更新parent_id
			$this->db->query('update ' .$this->thandle['table'].' set parent_id=' . $parent.' where id=' . $src['id']);	
		}
	  $treesize = $src['r']-$src['l']+1;
	  $this->_shiftRLValues($to, $treesize, $src['root_id']);
	  if($src['l'] >= $to){ // src was shifted too?
		$src['l'] += $treesize;
		$src['r'] += $treesize;
	  }
	  /* now there's enough room next to target to move the subtree*/
	  $newpos = 
	  $this->_shiftRLRange($src['l'], $src['r'], $to-$src['l'], $src['root_id']);
	  /* correct values after source */
	  $this->_shiftRLValues($src['r']+1, -$treesize, $src['root_id']);
	  
	  $this->db->query('UNLOCK TABLES');
	  if($src['l'] <= $to){ // dst was shifted too?
		$newpos['l'] -= $treesize;
		$newpos['r'] -= $treesize;
	  }  
	  return $newpos;
	}

	/* ******************************************************************* */
	/* Tree Destructors */
	/* ******************************************************************* */

	function nstDeleteTree ($node)
	/* deletes the entire tree structure including all records. */
	{
	  $res = $this->db->query("DELETE FROM ".$this->thandle['table']." WHERE root_id=" . $node['root_id']);
	  if (!$res) {$this->_prtError();}
	}

	function nstDelete ($node)
	/* deletes the node '$node' and all its children (subtree). */
	{
	  $leftanchor = $node['l'];
	  $this->db->query('LOCK TABLE ' . $this->thandle['table'] . ' WRITE');
	  $res = $this->db->query("DELETE FROM ".$this->thandle['table']." WHERE "
			 .$this->thandle['lvalname'].">=".$node['l']." AND ".$this->thandle['rvalname']."<=".$node['r']." AND root_id=".$node['root_id']);
	  $this->_shiftRLValues($node['r']+1, $node['l'] - $node['r'] -1, $node['root_id']);
	  $this->db->query('UNLOCK TABLES');
	  if (!$res) {$this->_prtError();}
	  return $this->nstGetNodeWhere ($this->thandle['lvalname']."<".$leftanchor
			   ." AND root_id=" . $node['root_id']
			   ." ORDER BY ".$this->thandle['lvalname']." DESC"
			 );
	}



	/* ******************************************************************* */
	/* Tree Queries */
	/*
	 * the following functions return a valid node (L and R-value), 
	 * or L=0,R=0 if the result doesn't exist.
	 */
	/* ******************************************************************* */

	function nstGetNodeWhere ($whereclause, $root_id = 0)
	/* returns the first node that matches the '$whereclause'. 
	   The WHERE-caluse can optionally contain ORDER BY or LIMIT clauses too. 
	 */
	{
	  $noderes['l'] = 0;
	  $noderes['r'] = 0;
	  $noderes['root_id'] = $root_id;
	  if($root_id != 0){
		$res = $this->db->query("SELECT * FROM ".$this->thandle['table']." WHERE ".$whereclause ." AND root_id=" . $root_id);
	  }else{
		$res = $this->db->query("SELECT * FROM ".$this->thandle['table']." WHERE ".$whereclause);
		
	  }
	  
	  if (!$res) {$this->_prtError();}
	  else{
		if ($row = $res->row_array()) {
		  $noderes = $row;
		  $noderes['l'] = $row[$this->thandle['lvalname']];
		  $noderes['r'] = $row[$this->thandle['rvalname']];
		  $noderes['root_id'] = $row['root_id'];
		}
	  }
	  return $noderes;
	}

	function nstGetNodeWhereLeft ($node, $leftval)
	/* returns the node that matches the left value 'leftval'. 
	 */
	{ return $this->nstGetNodeWhere($this->thandle['lvalname']."=".$leftval." AND root_id=" . $node['root_id']);
	}
	function nstGetNodeWhereRight ($node, $rightval)
	/* returns the node that matches the right value 'rightval'. 
	 */
	{ return $this->nstGetNodeWhere($this->thandle['rvalname']."=".$rightval." AND root_id=" . $node['root_id']);
	}

	function nstRoot ($root_id)
	/* returns the first node that matches the '$whereclause' */
	{ return $this->nstGetNodeWhere ($this->thandle['lvalname']."=1" . ' AND root_id=' . $root_id);
	}

	function nstFirstChild ($node)
	{ return $this->nstGetNodeWhere ($this->thandle['lvalname']."=".($node['l']+1) . ' AND root_id=' . $node['root_id']);
	}
	function nstLastChild ($node)
	{ return $this->nstGetNodeWhere ($this->thandle['rvalname']."=".($node['r']-1) . ' AND root_id=' . $node['root_id']);
	}
	function nstPrevSibling ($node)
	{ return $this->nstGetNodeWhere ($this->thandle['rvalname']."=".($node['l']-1) . ' AND root_id=' . $node['root_id']);
	}
	function nstNextSibling ($node)
	{ return $this->nstGetNodeWhere ($this->thandle['lvalname']."=".($node['r']+1) . ' AND root_id=' . $node['root_id']);
	}
	function nstAncestor ($node)
	{ return $this->nstGetNodeWhere ($this->thandle['lvalname']."<".($node['l'])
			   ." AND ".$this->thandle['rvalname'].">".($node['r'])
			   ." AND root_id=".$node['root_id']
			   ." ORDER BY ".$this->thandle['rvalname'] .""
			 );
	}


	/* ******************************************************************* */
	/* Tree Functions */
	/*
	 * the following functions return a boolean value
	 */
	/* ******************************************************************* */

	function nstValidNode ($node)
	/* only checks, if L-value < R-value (does no db-query)*/
	{ return ($node['l'] < $node['r']);
	}
	function nstHasAncestor ($node)
	{ return $this->nstValidNode($this->nstAncestor($node));
	}
	function nstHasPrevSibling ($node)
	{ return $this->nstValidNode($this->nstPrevSibling($node));
	}
	function nstHasNextSibling ($node)
	{ return $this->nstValidNode($this->nstNextSibling($node));
	}
	function nstHasChildren ($node)
	{ return (($node['r']-$node['l'])>1);
	}
	function nstIsRoot ($node)
	{ return ($node['l']==1);
	}
	function nstIsLeaf ($node)
	{ return (($node['r']-$node['l'])==1);
	}
	function nstIsChild ($node1, $node2)
	/* returns true, if 'node1' is a direct child or in the subtree of 'node2' */
	{ return (($node1['l']>$node2['l']) and ($node1['r']<$node2['r']));
	}
	function nstIsChildOrEqual ($node1, $node2)
	{ return (($node1['l']>=$node2['l']) and ($node1['r']<=$node2['r']));
	}
	function nstEqual ($node1, $node2)
	{ return (($node1['l']==$node2['l']) and ($node1['r']==$node2['r']));
	}


	/* ******************************************************************* */
	/* Tree Functions */
	/*
	 * the following functions return an integer value
	 */
	/* ******************************************************************* */

	function nstNbChildren ($node)
	{ return (($node['r']-$node['l']-1)/2);
	}

	function nstLevel ($node)
	/* returns node level. (root level = 0)*/
	{ 
	  $res = $this->db->query("SELECT COUNT(*) AS level FROM ".$this->thandle['table']." WHERE "
					   .$this->thandle['lvalname']."<".($node['l'])
			   ." AND ".$this->thandle['rvalname'].">".($node['r'])
			   ." AND root_id=".$node['root_id']
			 );
			   
	  if ($row = $res->row_array()) {
		return $row["level"];
	  }else{
		return 0;
	  }
	}

	/* ******************************************************************* */
	/* Tree Walks  */
	/* ******************************************************************* */

	function nstWalkPreorder ($node, $root = FALSE)
	/* initializes preorder walk and returns a walk handle */
	{
		if($root){
			$sql = "SELECT (count(parent.id)-1) as depth,node.* FROM ".$this->thandle['table']." AS node,".$this->thandle['table']." AS parent WHERE node.".$this->thandle['lvalname']." BETWEEN parent.".$this->thandle['lvalname']." AND parent.".$this->thandle['rvalname']." AND parent.root_id=".$node['root_id']." AND node.root_id=".$node['root_id']." GROUP BY node.id ORDER BY node.".$this->thandle['lvalname'];
		}else{
			$sql = "SELECT (count(parent.id)-1) as depth,node.* FROM ".$this->thandle['table']." AS node,".$this->thandle['table']." AS parent WHERE node.".$this->thandle['lvalname']." BETWEEN parent.".$this->thandle['lvalname']." AND parent.".$this->thandle['rvalname']." AND parent.root_id=".$node['root_id']." AND node.root_id=".$node['root_id']." GROUP BY node.id having node.parent_id<>0 ORDER BY node.".$this->thandle['lvalname'];
	  }
	  // var_dump($sql);
	  $res = $this->db->query($sql);

	  return array('recset'=>$res,
				   'prevl'=>$node['l'], 'prevr'=>$node['r'], // needed to efficiently calculate the level
				   'level'=>-2 );
	}

	function nstWalkNext(&$walkhand)
	{
	  // if ($row = mysql_fetch_array ($walkhand['recset'], MYSQL_ASSOC)){
	  if($row = $walkhand['recset']->row_array()){
		// calc level
		$walkhand['level']+= $walkhand['prevl'] - $row[$this->thandle['lvalname']] +2;
		// store current node
		$walkhand['prevl'] = $row[$this->thandle['lvalname']];
		$walkhand['prevr'] = $row[$this->thandle['rvalname']];
		$walkhand['row']   = $row;
		return array('l'=>$row[$this->thandle['lvalname']], 'r'=>$row[$this->thandle['rvalname']]);
	  } else{
		return FALSE;
	  }
	}

	function nstWalkAttribute($walkhand, $attribute)
	{
	  return $walkhand['row'][$attribute];
	}

	function nstWalkCurrent($walkhand)
	{
	  return array('l'=>$walkhand['prevl'], 'r'=>$walkhand['prevr']);
	}
	function nstWalkLevel($walkhand)
	{
	  return $walkhand['level'];
	}



	/* ******************************************************************* */
	/* Printing Tools */
	/* ******************************************************************* */

	function nstNodeAttribute ($node, $attribute)
	/* returns the attribute of the specified node */
	{
	  $res = $this->db->query("SELECT * FROM ".$this->thandle['table']." WHERE ".$this->thandle['lvalname']."=".$node['l'] . " AND root_id=" . $node['root_id']);
	  if ($row = $res->row_array()) {
		return $row[$attribute];
	  }else{
		return "";
	  }
	}

	function nstPrintSubtree ($node, $attributes)
	/*  */
	{
	  
        $wlk = $this->nstWalkPreorder($node);
		
        $depth = 0;
        $html = '';
        foreach ($wlk['recset']->result_array() as $row) {
			
            // print indentation
            $newDepth = intval($row['depth']);
            if ($newDepth > $depth) {
                while ($newDepth > $depth) {
                    $html.='<ul><li>';
                    $depth++;
                }
            } else if ($newDepth < $depth) {
                while ($newDepth < $depth) {
                    $html.="</li></ul>\n";
                    $depth--;
                }
                $html.='</li><li>';
            } else if ($newDepth === $depth) {
                if ($depth === 0 && $newDepth === 0) {
                    $html.='<ul class="red treeview"><li>';
                } else {
                    $html.='</li><li>';
                }
            }
            $html.='<a href="">';
            // print attributes
            $att = reset($attributes);
            while ($att) {
                // next line is more efficient:  print ($att.":".nstWalkAttribute($thandle, $wlk, $att));
                $html.=$row[$att];
                $att = next($attributes);
            }
            $html.='</a>';
            $depth = $newDepth;
        }
        if (count($this->nstHasChildren($this->nstRoot($node['root_id']))) > 0) {
            do {
                $html .= '</li></ul>';
            } while ($depth-- > 0);
        }
        echo $html;	  
	  
	  
	}

	function nstPrintSubtreeOLD ($node, $attributes)
	/*  */
	{
	  $res = $this->db->query("SELECT * FROM ".$this->thandle['table']." ORDER BY ".$this->thandle['lvalname']);
	  if (!$res) {$this->_prtError();}
	  else{
		$level = -1;
		$prevl = 0;
		$results = $res->result_array();
		foreach($results as $row){
		  // calc level
		  if      ($row[$this->thandle['lvalname']] == ($prevl+1)) {
			$level+=1;
		  }elseif ($row[$this->thandle['lvalname']] != ($prevr+1)) {
			$level-=1;
		  }
		  // print indentation
		  print (str_repeat("&nbsp;", $level*4));
		  // print attributes
		  $att = reset($attributes);
		  while($att){
			print ($att.":".$row[$att]);
			$att = next($attributes);
		  }
		  print ("<br/>");
		  $prevl = $row[$this->thandle['lvalname']];
		  $prevr = $row[$this->thandle['rvalname']];
		}
	  }
	}

	function nstPrintTree ($node, $attributes)
	/* Prints attributes of the entire tree. */
	{ 
	  $this->nstPrintSubtree ($this->nstRoot($node['root_id']), $attributes);
	}


	function nstBreadcrumbsString ($node)
	/* returns a string representing the breadcrumbs from $node to $root  
	   Example: "root > a-node > another-node > current-node"

	   Contributed by Nick Luethi
	 */
	{
	  // current node
	  $ret = $this->nstNodeAttribute ($node, "name");
	  // treat ancestor nodes

        while (!$this->nstIsRoot($ancnode=$this->nstAncestor($node))) {
            $ret = "<a href=''>" . $this->nstNodeAttribute($ancnode, $this->thandle['name']) . "</a> &gt; <a href=\"\"" . $ret . "</a>";
            $node = $ancnode;
        }	  
	  return $ret;
	  //return "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;breadcrumb: <font size='1'>".$ret."</font>";
	} 

	/* ******************************************************************* */
	/* internal functions */
	/* ******************************************************************* */

	function _prtError(){
	  echo "<p>Error: ".$this->db->_error_number().": ".$this->db->_error_message()."</p>";
	}

}
// end file