<?php
namespace App\Models;

class OrgNode extends Model
{
    protected string $table = 'org_nodes';

    public function tree(): array
    {
        $nodes = $this->all();
        $tree = [];
        $children = [];
        foreach ($nodes as $node) {
            $children[$node['parent_id']][] = $node;
        }
        $buildTree = function ($parentId) use (&$buildTree, $children) {
            $branch = [];
            foreach ($children[$parentId] ?? [] as $child) {
                $child['children'] = $buildTree($child['id']);
                $branch[] = $child;
            }
            return $branch;
        };
        $tree = $buildTree(null);
        return $tree;
    }
}
