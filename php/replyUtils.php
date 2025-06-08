<?php 

class ReviewNode {
    public $id;
    public $children = [];
    public $review;
}

function addChildrenRec($currentNode, $nodes, $adjacency){
    if(array_key_exists($currentNode->id, $adjacency)){
        $children = $adjacency[$currentNode->id];
        foreach($children as $childId){
            $child = $nodes[$childId];
            $currentNode->children[] = $child;
            addChildrenRec($child, $nodes, $adjacency);
        }
    }
}

function createReviewTree($reviews){ 
    $adjacency = [];
    $nodes = [];

    $root = new ReviewNode();
    $root->id = 0;
    //Since this comes from the database, we know ids are strictly greater than zero,
    //so we can use zero for the root node
    $nodes[0] = $root;

    foreach($reviews as $review){
        $id = $review["id"];
        $idParent = $review["inRispostaA"];
        $reviewNode = new ReviewNode();
        $reviewNode->id = $id;
        $reviewNode->review = $review;

        $nodes[$id] = $reviewNode;
        if($idParent != null){
            $adjacency[$idParent][] = $id;
        } else {
            $adjacency[0][] = $id;
        }
    }

    addChildrenRec($root, $nodes, $adjacency);

    return $root;

    /*
    $nodes = [];
    $root = new ReviewNode();

    foreach($reviews as $review){
        $node = new ReviewNode();
        $node->id = $review["id"];
        $node->idParent = $review["inRispostaA"];
        $node->children = [];
        $node->review = $review;
        $nodes[] = $node;
    }

    //Initial subdivision
    $internal = [];
    $leafs = [];
    foreach($nodes as $node){
        $isLeaf = true;
        foreach($nodes as $otherNode){
            if($node->id === $otherNode->idParent){
                //$node can't be a leaf
                $isLeaf = false;
                break;
            }
        }
        if($isLeaf){
            $leafs[] = $node; 
        } else {
            $internal[] = $node; 
        }
    } 

    //Single round
    $moved = [];
    for( $i = count($leafs) - 1; $i >= 0; $i--){
        $leaf = $leafs[$i];
        for( $j = count($internal) - 1; $j >= 0; $j-- ){
            $parent = $internal[$j];
            if($parent->id === $leaf->idParent){
                //Remove $leaf from leafs
                array_splice($leafs, $i, 1); 
                //add to parent's children
                $parent->children[] = $leaf;
                $moved[] = $j;
            }
        }
    }

    foreach($moved as $index){
        $leafs[] = $internal[$index];
        unset($internal[$index]);
    }


    //Add remaining leafs to root
    foreach($leafs as $leaf){
        $root->children[] = $leaf;
    }

    return $root; 
    */
} ?>
