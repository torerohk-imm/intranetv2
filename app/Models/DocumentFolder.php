<?php
namespace App\Models;

class DocumentFolder extends Model
{
    protected string $table = 'document_folders';

    public function tree(): array
    {
        $folders = $this->all();
        $tree = [];
        $children = [];
        foreach ($folders as $folder) {
            $children[$folder['parent_id']][] = $folder;
        }
        $build = function ($parentId) use (&$build, $children) {
            $branch = [];
            foreach ($children[$parentId] ?? [] as $child) {
                $child['children'] = $build($child['id']);
                $branch[] = $child;
            }
            return $branch;
        };
        return $build(null);
    }
}
